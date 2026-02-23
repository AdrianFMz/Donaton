@extends('layouts.main')

@section('content')
  <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
    <div class="grid gap-8 md:grid-cols-2 md:items-center">
      <div>
        <h1 class="text-3xl font-bold tracking-tight md:text-4xl">
          Donativos transparentes para causas reales
        </h1>
        <p class="mt-3 text-slate-300">
          Elige una causa, dona la cantidad que quieras y recibe confirmación. Tu apoyo cambia vidas.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
          @auth
            <a href="{{ route('causas.index') }}"
               class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-400">
              Ver causas
            </a>
          @else
            <a href="{{ route('register') }}"
               class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-400">
              Crear cuenta
            </a>
            <a href="{{ route('login') }}"
               class="rounded-xl bg-white/10 px-5 py-3 text-sm font-medium hover:bg-white/15">
              Iniciar sesión
            </a>
          @endauth
        </div>
      </div>

      {{-- Placeholder imagen hero --}}
      <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <div class="aspect-video w-full bg-gradient-to-br from-indigo-500/20 via-white/5 to-emerald-500/10"></div>
        <div class="p-4 text-xs text-slate-300">
          *Aquí irá tu imagen principal (la pondrás manualmente).
        </div>
      </div>
    </div>
  </section>

  <section class="mt-10">
    <h2 class="text-xl font-semibold">¿Cómo funciona?</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-3">
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
        <div class="text-sm font-semibold">1) Elige una causa</div>
        <div class="mt-2 text-sm text-slate-300">Selecciona la problemática que quieres apoyar.</div>
      </div>
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
        <div class="text-sm font-semibold">2) Dona lo que gustes</div>
        <div class="mt-2 text-sm text-slate-300">Sin mínimo. En MXN. Pago seguro.</div>
      </div>
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
        <div class="text-sm font-semibold">3) Confirmación</div>
        <div class="mt-2 text-sm text-slate-300">Recibes confirmación y puedes ver tu historial.</div>
      </div>
    </div>
  </section>
@endsection