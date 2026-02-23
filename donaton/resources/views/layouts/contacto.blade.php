@extends('layouts.main')

@section('content')
  <div class="grid gap-8 md:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
      <h1 class="text-2xl font-bold">Contacto</h1>
      <p class="mt-2 text-slate-300">Envíanos un mensaje y te responderemos lo antes posible.</p>

      <form method="POST" action="{{ route('contacto.send') }}" class="mt-6 space-y-4">
        @csrf

        <div>
          <label class="text-sm text-slate-200">Nombre</label>
          <input name="nombre" value="{{ old('nombre') }}"
                 class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400" />
          @error('nombre') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="text-sm text-slate-200">Email</label>
          <input name="email" value="{{ old('email') }}"
                 class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400" />
          @error('email') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="text-sm text-slate-200">Mensaje</label>
          <textarea name="mensaje" rows="5"
            class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400">{{ old('mensaje') }}</textarea>
          @error('mensaje') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
        </div>

        <button type="submit"
          class="w-full rounded-xl bg-indigo-500 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-400">
          Enviar
        </button>
      </form>
    </section>

    <aside class="rounded-3xl border border-white/10 bg-white/5 p-8">
      <h2 class="text-lg font-semibold">Información</h2>
      <p class="mt-2 text-sm text-slate-300">
        Este espacio es para que pongas datos reales después (correo, redes, ubicación, etc.).
      </p>

      <div class="mt-6 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
        <div class="aspect-video bg-gradient-to-br from-white/5 to-white/10"></div>
        <div class="p-4 text-xs text-slate-300">*Placeholder para imagen/mapa.</div>
      </div>
    </aside>
  </div>
@endsection