<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Cause;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    private function baseUrl(): string
    {
        return config('services.paypal.mode', 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function currency(): string
    {
        return config('services.paypal.currency', 'MXN');
    }

    private function accessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $secret   = config('services.paypal.client_secret');

        $res = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$res->ok()) {
            throw new \RuntimeException('PayPal OAuth failed: ' . $res->status() . ' ' . $res->body());
        }

        return (string) $res->json('access_token');
    }

    public function start(StoreDonationRequest $request, string $slug)
    {
        $cause = Cause::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $donation = Donation::firstOrCreate(
            ['client_ref' => $request->input('client_ref')],
            [
                'user_id' => auth()->id(),
                'cause_id' => $cause->id,
                'amount_mxn' => (float) $request->input('amount_mxn'),
                'message' => $request->input('message'),
                'status' => 'pending',
            ]
        );

        try {
            $token = $this->accessToken();
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo iniciar PayPal (token). Intenta de nuevo.');
        }

        $orderBody = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $donation->client_ref,
                'custom_id'    => (string) $donation->id,
                'description'  => 'Donación - ' . $cause->title,
                'amount' => [
                    'currency_code' => $this->currency(),
                    'value' => number_format((float) $donation->amount_mxn, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'brand_name' => 'DONATON',
                'user_action' => 'PAY_NOW',
                'return_url' => route('paypal.return', [], true),
                'cancel_url' => route('paypal.cancel', [], true),
            ],
        ];

        $res = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl() . '/v2/checkout/orders', $orderBody);

        if ($res->status() !== 201) {
            return back()->with('error', 'PayPal rechazó la orden. Intenta con otra cuenta o más tarde.');
        }

        $order   = $res->json();
        $orderId = $order['id'] ?? null;

        Payment::create([
            'donation_id' => $donation->id,
            'provider' => 'paypal',
            'provider_ref' => $orderId,
            'status' => 'created',
            'payload' => ['order' => $order],
        ]);

        $approveUrl = null;
        foreach (($order['links'] ?? []) as $lnk) {
            if (($lnk['rel'] ?? '') === 'approve') {
                $approveUrl = $lnk['href'] ?? null;
                break;
            }
        }

        if (!$approveUrl) {
            return back()->with('error', 'PayPal no devolvió el enlace de pago. Intenta de nuevo.');
        }

        return redirect()->away($approveUrl);
    }

    public function return()
    {
        $orderId = request()->query('token');
        if (!$orderId) {
            return redirect()->route('donaciones.mine')
                ->with('error', 'PayPal no regresó el identificador del pago. Intenta nuevamente.');
        }

        $paymentRow = Payment::query()
            ->where('provider', 'paypal')
            ->where('provider_ref', $orderId)
            ->latest()
            ->first();

        if (!$paymentRow) {
            return redirect()->route('donaciones.mine')
                ->with('error', 'No se encontró el pago de PayPal en tu sistema.');
        }

        $donation = Donation::find($paymentRow->donation_id);
        if (!$donation) {
            return redirect()->route('donaciones.mine')
                ->with('error', 'No se encontró la donación asociada a este pago.');
        }

        try {
            $token = $this->accessToken();

            // 1) Consultar estado del order antes de capturar
            $orderRes = Http::withToken($token)
                ->acceptJson()
                ->get($this->baseUrl() . "/v2/checkout/orders/{$orderId}");

            if ($orderRes->ok()) {
                $orderData = $orderRes->json();
                $orderStatus = strtoupper((string) ($orderData['status'] ?? ''));

                $paymentRow->payload = array_merge((array) $paymentRow->payload, ['order_get' => $orderData]);
                $paymentRow->save();

                // Si ya está COMPLETED, marcamos pagado y listo
                if ($orderStatus === 'COMPLETED') {
                    $donation->status = 'paid';
                    $donation->save();

                    $paymentRow->status = 'approved';
                    $paymentRow->save();

                    return redirect()->route('donaciones.mine')
                        ->with('success', ' ¡Donativo realizado con PayPal! Gracias por tu apoyo, lo agradecerán!');
                }

                // Si no está aprobado, no intentes capture
                if ($orderStatus !== 'APPROVED') {
                    return redirect()->route('donaciones.mine')
                        ->with('error', ' Tu pago aún no se confirmó en PayPal. Si se quedó abierto el checkout, intenta finalizarlo.');
                }
            }

            // 2) Capture (PayPal puede devolver 201 como éxito)
            $cap = Http::withToken($token)
                ->acceptJson()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl() . "/v2/checkout/orders/{$orderId}/capture", (object) []);

            if (!$cap->successful()) {
                $http = $cap->status();
                $raw  = $cap->body();

                $err = $cap->json();
                if (!is_array($err)) $err = [];

                $paymentRow->status = 'error';
                $paymentRow->payload = array_merge((array) $paymentRow->payload, [
                    'capture_http' => $http,
                    'capture_error' => $err ?: $raw,
                ]);
                $paymentRow->save();

                Log::error('PayPal capture failed', ['http' => $http, 'body' => $raw]);

                return redirect()->route('donaciones.mine')
                    ->with('error', ' No se pudo completar el pago con PayPal. Intenta nuevamente o usa otro método.');
            }

            $capture = $cap->json();
            $status = strtoupper((string) ($capture['status'] ?? ''));

            if ($status === 'COMPLETED') {
                $donation->status = 'paid';
                $donation->save();
                $paymentRow->status = 'approved';

                $paymentRow->payload = array_merge((array) $paymentRow->payload, ['capture' => $capture]);
                $paymentRow->save();

                return redirect()->route('donaciones.mine')
                    ->with('success', ' ¡Donativo realizado con PayPal! Gracias por tu apoyo, lo agradecerán!');
            }

            // Otros estados (poco comunes en capture)
            $paymentRow->status = strtolower($status ?: 'pending');
            $paymentRow->payload = array_merge((array) $paymentRow->payload, ['capture' => $capture]);
            $paymentRow->save();

            return redirect()->route('donaciones.mine')
                ->with('success', '⏳ Tu pago fue enviado a PayPal y está en verificación. Revisa tu historial en unos momentos.');
        } catch (\Throwable $e) {
            Log::error('PayPal return error', ['msg' => $e->getMessage()]);

            return redirect()->route('donaciones.mine')
                ->with('error', ' Ocurrió un problema al procesar tu pago con PayPal. Intenta nuevamente.');
        }
    }

    public function cancel()
    {
        return redirect()->route('donaciones.mine')
            ->with('error', ' Pago cancelado en PayPal. Si fue un error, puedes intentarlo nuevamente.');
    }
}