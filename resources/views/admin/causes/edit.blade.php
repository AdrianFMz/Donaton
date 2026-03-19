@extends('layouts.main')

@section('content')
  <div class="flex items-start justify-between gap-4">
    <div>
      <a href="{{ route('admin.causes.index') }}" class="text-sm text-slate-300 hover:text-slate-100">
        ← Volver a Admin Causas
      </a>
      <h1 class="mt-2 text-2xl font-bold">Editar causa</h1>
      <p class="mt-2 text-slate-300">
        Estos textos se mostrarán en la página de detalle: <span class="text-slate-100 font-semibold">{{ $cause->title }}</span>
      </p>
      <p class="mt-1 text-xs text-slate-400">
        Imágenes: public/images/causes/{{ $cause->slug }}/ (cover.png, 1.png, 2.png...)
      </p>
    </div>

    <a href="{{ route('causas.show', $cause->slug) }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold hover:bg-white/15">
      Ver página
    </a>
  </div>

  <form method="POST" action="{{ route('admin.causes.update', $cause) }}" class="mt-6 space-y-4">
    @csrf
    @method('PUT')

    <div class="grid gap-4 md:grid-cols-2">
      <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <label class="text-sm font-semibold">Título</label>
        <input name="title" value="{{ old('title', $cause->title) }}"
               class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400" />
        @error('title') <div class="mt-2 text-xs text-rose-300">{{ $message }}</div> @enderror
      </div>

      <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <label class="text-sm font-semibold">Descripción corta</label>
        <textarea name="short_description" rows="3"
                  class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400">{{ old('short_description', $cause->short_description) }}</textarea>
        @error('short_description') <div class="mt-2 text-xs text-rose-300">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <label class="text-sm font-semibold">La problemática</label>
      <textarea name="problem_description" rows="6"
                class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400">{{ old('problem_description', $cause->problem_description) }}</textarea>
      @error('problem_description') <div class="mt-2 text-xs text-rose-300">{{ $message }}</div> @enderror
    </div>

    <div class="grid gap-4 md:grid-cols-2">
      <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <label class="text-sm font-semibold">¿Desde cuándo está esta causa?</label>
        <input name="since_date" value="{{ old('since_date', $cause->since_date) }}" placeholder="Ej: Enero 2024"
               class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400" />
        @error('since_date') <div class="mt-2 text-xs text-rose-300">{{ $message }}</div> @enderror
      </div>

      <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <label class="text-sm font-semibold">Impacto esperado</label>
        <textarea name="impact" rows="4"
                  class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400">{{ old('impact', $cause->impact) }}</textarea>
        @error('impact') <div class="mt-2 text-xs text-rose-300">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <label class="text-sm font-semibold">¿Qué se hará con el dinero recaudado?</label>
      <textarea name="use_of_funds" rows="6"
                class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400">{{ old('use_of_funds', $cause->use_of_funds) }}</textarea>
      @error('use_of_funds') <div class="mt-2 text-xs text-rose-300">{{ $message }}</div> @enderror
    </div>

    <div class="flex flex-wrap gap-3">
      <button class="rounded-xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-400">
        Guardar cambios
      </button>

      <a href="{{ route('admin.causes.index') }}"
         class="rounded-xl bg-white/10 px-6 py-3 text-sm font-semibold hover:bg-white/15">
        Cancelar
      </a>
    </div>
  </form>
@endsection