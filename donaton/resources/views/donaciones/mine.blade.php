@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Mis donativos</h1>
  <p class="mt-2 text-slate-300">Aquí podrás ver lo que has donado por causa.</p>

  {{-- Totales por causa --}}
  <div class="mt-6 grid gap-4 md:grid-cols-3">
    @foreach($causes as $c)
      @php
        $total = $totalsByCauseId[$c->id] ?? 0;
      @endphp
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
        <div class="text-sm text-slate-300">{{ $c->title }}</div>
        <div class="mt-2 text-2xl font-semibold">${{ number_format($total, 2) }}</div>
        <div class="mt-1 text-xs text-slate-400">Total donado (MXN)</div>
      </div>
    @endforeach
  </div>

  {{-- Lista --}}
  <div class="mt-8 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
    <div class="p-6">
      <div class="text-sm font-semibold">Últimos donativos</div>

      @if($donations->isEmpty())
        <div class="mt-3 text-sm text-slate-300">Aún no tienes donativos registrados.</div>
      @else
        <div class="mt-4 space-y-3">
          @foreach($donations as $d)
            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
              <div class="flex items-center justify-between gap-4">
                <div class="text-sm font-semibold">{{ $d->cause->title }}</div>
                <div class="text-sm text-slate-200">${{ number_format((float)$d->amount_mxn, 2) }} MXN</div>
              </div>
                @php
                $status = strtolower($d->status);

                $statusLabel = match ($status) {
                    'paid' => 'Pagado',
                    'pending' => 'Pendiente',
                    'failed' => 'Fallido',
                    'cancelled' => 'Cancelado',
                    default => strtoupper($d->status),
                };

                $statusClasses = match ($status) {
                    'paid' => 'bg-emerald-500/15 text-emerald-200 border-emerald-500/30',
                    'pending' => 'bg-amber-500/15 text-amber-200 border-amber-500/30',
                    'failed' => 'bg-rose-500/15 text-rose-200 border-rose-500/30',
                    'cancelled' => 'bg-slate-500/15 text-slate-200 border-slate-500/30',
                    default => 'bg-white/10 text-slate-200 border-white/10',
                };
                @endphp

                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                <span class="inline-flex items-center rounded-full border px-2.5 py-1 {{ $statusClasses }}">
                    {{ $statusLabel }}
                </span>
                <span>•</span>
                <span>{{ $d->created_at->format('d/m/Y H:i') }}</span>
                @php


                $lastPayment = $d->payments->last();
                $providerLabel = $lastPayment?->provider === 'mercadopago' ? 'Mercado Pago'
                                : ($lastPayment?->provider === 'paypal' ? 'PayPal' : '—');
                @endphp

                <div class="mt-2 text-xs text-slate-400">
                Método: <span class="text-slate-200">{{ $providerLabel }}</span>
                </div>
                </div>

                
              @if($d->message)
                <div class="mt-2 text-sm text-slate-300">“{{ $d->message }}”</div>
              @endif
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection