@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Dashboard</h1>
  <p class="mt-2 text-slate-300">Sprint 0: placeholder. Te redirigiremos según rol después.</p>

  <div class="mt-6">
    <a href="{{ route('causas.index') }}"
       class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-400">
      Ir a Causas
    </a>
  </div>
@endsection