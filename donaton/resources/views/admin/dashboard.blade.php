@extends('layouts.main')

@section('content')
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold">Dashboard Admin</h1>
      <p class="mt-1 text-slate-300 text-sm">Métricas de donativos (pagados y registros recientes).</p>
    </div>
  </div>

  {{-- KPIs --}}
  <div class="mt-6 grid gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Total recaudado (Pagado)</div>
      <div class="mt-2 text-2xl font-semibold">${{ number_format((float)$totalPaid, 2) }} MXN</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Donativos pagados</div>
      <div class="mt-2 text-2xl font-semibold">{{ number_format((int)$countPaid) }}</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Causas activas</div>
      <div class="mt-2 text-2xl font-semibold">{{ $causes->count() }}</div>
    </div>
  </div>

  {{-- Filtros --}}
  <form method="GET" class="mt-8 grid gap-3 rounded-3xl border border-white/10 bg-white/5 p-6 md:grid-cols-5">
    <div class="md:col-span-2">
      <label class="text-xs text-slate-300">Causa</label>
      <select name="cause_id" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-3 py-2 text-sm">
        <option value="">Todas</option>
        @foreach($causes as $c)
          <option value="{{ $c->id }}" @selected((string)$causeId === (string)$c->id)>{{ $c->title }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="text-xs text-slate-300">Estado</label>
      <select name="status" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-3 py-2 text-sm">
        <option value="">Todos</option>
        @foreach(['paid'=>'Pagado','pending'=>'Pendiente','failed'=>'Fallido','cancelled'=>'Cancelado'] as $k=>$v)
          <option value="{{ $k }}" @selected((string)$status === (string)$k)>{{ $v }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="text-xs text-slate-300">Desde</label>
      <input type="date" name="from" value="{{ $from }}"
        class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-3 py-2 text-sm" />
    </div>

    <div>
      <label class="text-xs text-slate-300">Hasta</label>
      <input type="date" name="to" value="{{ $to }}"
        class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-3 py-2 text-sm" />
    </div>

    <div class="md:col-span-5 flex gap-3">
      <button class="rounded-xl bg-indigo-500 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-400">
        Aplicar filtros
      </button>
      <a href="{{ route('admin.dashboard') }}" class="rounded-xl bg-white/10 px-5 py-2 text-sm font-semibold hover:bg-white/15">
        Limpiar
      </a>
    </div>
  </form>

  {{-- Totales por causa --}}
  <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6">
    <div class="text-sm font-semibold">Recaudación por causa (solo Pagado)</div>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-slate-300">
          <tr class="border-b border-white/10">
            <th class="py-2 text-left">Causa</th>
            <th class="py-2 text-right">Donativos</th>
            <th class="py-2 text-right">Total</th>
          </tr>
        </thead>
        <tbody>
          @forelse($byCause as $row)
            <tr class="border-b border-white/5">
              <td class="py-2">{{ $row->cause->title ?? '—' }}</td>
              <td class="py-2 text-right">{{ number_format((int)$row->total_count) }}</td>
              <td class="py-2 text-right">${{ number_format((float)$row->total_amount, 2) }} MXN</td>
            </tr>
          @empty
            <tr><td colspan="3" class="py-3 text-slate-300">Aún no hay donativos pagados.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Últimos donativos --}}
  <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6">
    <div class="text-sm font-semibold">Últimos donativos</div>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-slate-300">
          <tr class="border-b border-white/10">
            <th class="py-2 text-left">Fecha</th>
            <th class="py-2 text-left">Usuario</th>
            <th class="py-2 text-left">Causa</th>
            <th class="py-2 text-left">Estado</th>
            <th class="py-2 text-left">Método</th>
            <th class="py-2 text-right">Monto</th>
          </tr>
        </thead>
        <tbody>
          @foreach($latest as $d)
            @php
              $lastPayment = $d->payments->last();
              $method = $lastPayment?->provider === 'mercadopago' ? 'Mercado Pago' : ($lastPayment?->provider === 'paypal' ? 'PayPal' : '—');
            @endphp
            <tr class="border-b border-white/5">
              <td class="py-2">{{ $d->created_at->format('d/m/Y H:i') }}</td>
              <td class="py-2">{{ $d->user->name ?? '—' }}</td>
              <td class="py-2">{{ $d->cause->title ?? '—' }}</td>
              <td class="py-2">{{ strtoupper($d->status) }}</td>
              <td class="py-2">{{ $method }}</td>
              <td class="py-2 text-right">${{ number_format((float)$d->amount_mxn, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection