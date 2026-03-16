@extends('layouts.main')

@section('content')
  @php
    $title = match($result) {
      'success' => '¡Gracias por tu donativo!',
      'pending' => 'Tu pago está pendiente',
      'failure' => 'No se pudo completar el pago',
      default => 'Resultado del pago',
    };

    $desc = match($result) {
      'success' => 'Estamos validando tu pago. Si Mercado Pago lo confirma, tu donativo se marcará como PAGADO en “Mis donativos”.',
      'pending' => 'Mercado Pago indicó que el pago sigue pendiente. En cuanto se confirme, verás el cambio en “Mis donativos”.',
      'failure' => 'El pago no se completó. Puedes intentarlo de nuevo cuando gustes.',
      default => '',
    };
  @endphp

  <div class="rounded-3xl border border-white/10 bg-white/5 p-8">
    <h1 class="text-2xl font-bold">{{ $title }}</h1>
    <p class="mt-3 text-slate-300">{{ $desc }}</p>

    <div class="mt-6 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
      <div class="aspect-video bg-gradient-to-br from-indigo-500/20 via-white/5 to-emerald-500/10"></div>
      <div class="p-4 text-xs text-slate-300">*Aquí irá una imagen emotiva (la pondrás manualmente).</div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
      <a href="{{ route('donaciones.mine') }}"
         class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400">
        Ir a Mis donativos
      </a>
      <a href="{{ route('causas.index') }}"
         class="rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold hover:bg-white/15">
        Ver causas
      </a>
    </div>
  </div>
@endsection