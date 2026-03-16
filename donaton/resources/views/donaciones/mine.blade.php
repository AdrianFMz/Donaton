@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Mis donativos</h1>
  <p class="mt-2 text-slate-300">
    Sprint 1: aquí se mostrará tu historial por causa (Sprint 2 lo conectamos a BD).
  </p>

  <div class="mt-6 grid gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Salud</div>
      <div class="mt-2 text-2xl font-semibold">—</div>
      <div class="mt-1 text-xs text-slate-400">Total donado</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Educación</div>
      <div class="mt-2 text-2xl font-semibold">—</div>
      <div class="mt-1 text-xs text-slate-400">Total donado</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Alimentos</div>
      <div class="mt-2 text-2xl font-semibold">—</div>
      <div class="mt-1 text-xs text-slate-400">Total donado</div>
    </div>
  </div>

  <div class="mt-8 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
    <div class="p-6">
      <div class="text-sm font-semibold">Últimos donativos</div>
      <div class="mt-3 text-sm text-slate-300">
        (tabla/lista aquí — Sprint 2)
      </div>
    </div>
  </div>
@endsection