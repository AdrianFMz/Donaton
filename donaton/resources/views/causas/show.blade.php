@extends('layouts.main')

@section('content')
  <div class="grid gap-8 md:grid-cols-3">
    <section class="md:col-span-2">
      <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <div class="aspect-video bg-white/5"></div>
        <div class="p-4 text-xs text-slate-300">*Placeholder imagen principal</div>
      </div>

      <h1 class="mt-6 text-3xl font-bold">{{ $cause->title }}</h1>
      <p class="mt-3 text-slate-300">{{ $cause->short_description }}</p>

      <div class="mt-8 space-y-6">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
          <div class="text-sm font-semibold">Problemática</div>
          <p class="mt-2 text-sm text-slate-300">
            {{ $cause->problem_description ?? 'Aquí pondrás la problemática a detalle.' }}
          </p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
          <div class="text-sm font-semibold">¿Qué se hará con el dinero?</div>
          <p class="mt-2 text-sm text-slate-300">
            {{ $cause->use_of_funds ?? 'Aquí pondrás el plan de uso de fondos.' }}
          </p>
        </div>
      </div>
    </section>

    <aside class="space-y-4">
      <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <div class="text-sm font-semibold">Desde</div>
        <div class="mt-2 text-sm text-slate-300">
          {{ $cause->since_date ? \Carbon\Carbon::parse($cause->since_date)->format('d/m/Y') : 'Pendiente' }}
        </div>
      </div>

      <a href="{{ route('donaciones.create', $cause->slug) }}"
         class="block rounded-2xl bg-indigo-500 px-6 py-4 text-center text-sm font-semibold text-white hover:bg-indigo-400">
        Donar a esta causa
      </a>

      <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <div class="text-sm font-semibold">Galería</div>
        <div class="mt-4 grid grid-cols-2 gap-3">
          <div class="aspect-square rounded-2xl border border-white/10 bg-white/5"></div>
          <div class="aspect-square rounded-2xl border border-white/10 bg-white/5"></div>
          <div class="aspect-square rounded-2xl border border-white/10 bg-white/5"></div>
          <div class="aspect-square rounded-2xl border border-white/10 bg-white/5"></div>
        </div>
        <div class="mt-3 text-xs text-slate-300">*Placeholders de imágenes</div>
      </div>
    </aside>
  </div>
@endsection