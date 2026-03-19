<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Mercado Pago suele enviar data.id (pago) en el body o query
        $paymentId = $request->input('data.id')
            ?? $request->query('data.id')
            ?? $request->input('id')
            ?? $request->query('id');

        if (!$paymentId) {
            return response()->json(['ok' => true]);
        }

        try {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
            $client = new PaymentClient();

            $mpPayment = $client->get((int) $paymentId);

            $externalRef = $mpPayment->external_reference ?? null; // nosotros pusimos client_ref aquí
            if (!$externalRef) {
                return response()->json(['ok' => true]);
            }

            $donation = Donation::query()->where('client_ref', $externalRef)->first();
            if (!$donation) {
                return response()->json(['ok' => true]);
            }

            $mpStatus = strtolower((string) ($mpPayment->status ?? ''));
            $newDonationStatus = match ($mpStatus) {
                'approved' => 'paid',
                'rejected' => 'failed',
                'cancelled' => 'cancelled',
                default => 'pending',
            };

            $donation->status = $newDonationStatus;
            $donation->save();

            // Actualiza el último payment "mercadopago" de esa donación
            $payRow = Payment::query()
                ->where('donation_id', $donation->id)
                ->where('provider', 'mercadopago')
                ->latest()
                ->first();

            $paymentArray = json_decode(json_encode($mpPayment), true);

            if ($payRow) {
                $existingPayload = is_array($payRow->payload) ? $payRow->payload : [];
                $payRow->provider_ref = (string) $paymentId; // ahora guardamos el payment_id
                $payRow->status = $mpStatus;
                $payRow->payload = array_merge($existingPayload, ['payment' => $paymentArray]);
                $payRow->save();
            } else {
                Payment::create([
                    'donation_id' => $donation->id,
                    'provider' => 'mercadopago',
                    'provider_ref' => (string) $paymentId,
                    'status' => $mpStatus,
                    'payload' => ['payment' => $paymentArray],
                ]);
            }

            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            Log::error('MP webhook error', ['msg' => $e->getMessage()]);
            // Igual respondemos 200 para que MP no reintente infinitamente mientras debuggeas
            return response()->json(['ok' => true]);
        }
    }
}