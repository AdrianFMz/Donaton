@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Dashboard Admin</h1>
  <p class="mt-2 text-slate-300">
    Sprint 0: placeholder. Aquí irán métricas por causa, usuarios y donativos.
  </p>

  <div class="mt-6 grid gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Total donativos</div>
      <div class="mt-2 text-2xl font-semibold">—</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Causa más apoyada</div>
      <div class="mt-2 text-2xl font-semibold">—</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
      <div class="text-sm text-slate-300">Usuarios registrados</div>
      <div class="mt-2 text-2xl font-semibold">—</div>
    </div>
  </div>

  <div class="mt-8">
    <a href="{{ route('causas.index') }}"
       class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-400">
      Ver causas (modo usuario)
    </a>
  </div>
@endsection