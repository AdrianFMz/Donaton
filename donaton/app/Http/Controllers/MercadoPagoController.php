<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Cause;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

class MercadoPagoController extends Controller
{
    private function isLocal(): bool
    {
        $appUrl = (string) config('app.url', '');
        return str_contains($appUrl, '127.0.0.1') || str_contains($appUrl, 'localhost');
    }

    private function mpSetup(): void
    {
        MercadoPagoConfig::setAccessToken((string) config('services.mercadopago.access_token'));

        // Recomendación del SDK para probar en localhost
        if ($this->isLocal()) {
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        }
    }

    public function start(StoreDonationRequest $request, string $slug)
    {
        $cause = Cause::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 1) Donación idempotente (si el usuario reenvía el form, no duplica)
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

        // 2) Preferencia MP
        $this->mpSetup();
        $client = new PreferenceClient();

        $preferenceData = [
            'items' => [
                [
                    'title' => 'Donación - ' . $cause->title,
                    'quantity' => 1,
                    'unit_price' => (float) $donation->amount_mxn,
                    'currency_id' => 'MXN',
                ],
            ],
            // Esto es CLAVE para poder “sincronizar” en local con payments/search
            'external_reference' => $donation->client_ref,
            'metadata' => [
                'donation_id' => $donation->id,
                'client_ref' => $donation->client_ref,
                'cause_slug' => $cause->slug,
            ],
        ];

        // En LOCAL no metas back_urls / notification_url (evitas errores por URLs no públicas)
        if (!$this->isLocal()) {
            $preferenceData['back_urls'] = [
                'success' => route('mp.return', ['result' => 'success'], true),
                'pending' => route('mp.return', ['result' => 'pending'], true),
                'failure' => route('mp.return', ['result' => 'failure'], true),
            ];
            $preferenceData['auto_return'] = 'approved';

            $notificationUrl = config('services.mercadopago.notification_url');
            if (!empty($notificationUrl)) {
                $preferenceData['notification_url'] = $notificationUrl;
            }
        }

        try {
            $preference = $client->create($preferenceData);
        } catch (\Throwable $e) {
            return back()->with('error', 'Mercado Pago rechazó la preferencia: ' . $e->getMessage());
        }

        // Log útil para diagnosticar el “seller real” (collector_id)
        Log::info('MP preference', [
            'preference_id' => $preference->id ?? null,
            'collector_id' => $preference->collector_id ?? null,
            'sandbox_init_point' => $preference->sandbox_init_point ?? null,
        ]);

        // 3) Guardar intento de pago
        Payment::create([
            'donation_id' => $donation->id,
            'provider' => 'mercadopago',
            'provider_ref' => $preference->id ?? null, // preference_id
            'status' => 'created',
            'payload' => [
                'preference_id' => $preference->id ?? null,
                'init_point' => $preference->init_point ?? null,
                'sandbox_init_point' => $preference->sandbox_init_point ?? null,
                'collector_id' => $preference->collector_id ?? null,
            ],
        ]);

        // 4) Redirigir a checkout (sandbox si estás probando)
        $useSandbox = filter_var(config('services.mercadopago.use_sandbox', true), FILTER_VALIDATE_BOOL);
        $checkoutUrl = $useSandbox ? ($preference->sandbox_init_point ?? null) : ($preference->init_point ?? null);

        if (!$checkoutUrl) {
            return back()->with('error', 'No se pudo generar la URL de checkout de Mercado Pago.');
        }

        return redirect()->away($checkoutUrl);
    }

    public function returnPage(string $result)
    {
        // En LOCAL casi no se usa (porque no metemos back_urls),
        // pero lo dejamos listo para cuando tengas URL pública.
        return view('donaciones.mp_return', compact('result'));
    }

    // ✅ Confirmación LOCAL (sin webhook): consulta payments/search por external_reference
    public function sync(Donation $donation)
    {
        if ($donation->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $token = (string) config('services.mercadopago.access_token');
        if (!$token) {
            return back()->with('error', 'Falta MP_ACCESS_TOKEN en .env');
        }

        $res = Http::withToken($token)->get('https://api.mercadopago.com/v1/payments/search', [
            'external_reference' => $donation->client_ref,
            'sort' => 'date_created',
            'criteria' => 'desc',
            'limit' => 1,
        ]);

        if (!$res->ok()) {
            return back()->with('error', 'No se pudo consultar MP (' . $res->status() . '): ' . $res->body());
        }

        $results = $res->json('results') ?? [];
        if (count($results) === 0) {
            return back()->with('error', 'Aún no hay pagos en Mercado Pago para este donativo.');
        }

        $mpPayment = $results[0];
        $mpStatus = strtolower((string) ($mpPayment['status'] ?? ''));
        $mpDetail = (string) ($mpPayment['status_detail'] ?? '');
        $mpId = (string) ($mpPayment['id'] ?? '');

        $donation->status = match ($mpStatus) {
            'approved' => 'paid',
            'rejected' => 'failed',
            'cancelled' => 'cancelled',
            default => 'pending',
        };
        $donation->save();

        $payRow = Payment::query()
            ->where('donation_id', $donation->id)
            ->where('provider', 'mercadopago')
            ->latest()
            ->first();

        if ($payRow) {
            $payload = is_array($payRow->payload) ? $payRow->payload : [];
            $payload['payment'] = $mpPayment;

            $payRow->status = $mpStatus;
            $payRow->provider_ref = $mpId ?: $payRow->provider_ref;
            $payRow->payload = $payload;
            $payRow->save();
        } else {
            Payment::create([
                'donation_id' => $donation->id,
                'provider' => 'mercadopago',
                'provider_ref' => $mpId,
                'status' => $mpStatus,
                'payload' => ['payment' => $mpPayment],
            ]);
        }

        return back()->with('success', "Estado MP: $mpStatus ($mpDetail)");
    }
}