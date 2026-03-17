@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Donar a: {{ $cause->title }}</h1>
  <p class="mt-2 text-slate-300">Elige una cantidad (MXN) y deja un mensaje opcional.</p>

  <div class="mt-6 grid gap-6 md:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
      <form x-data="{ submitting:false }"
              @submit="submitting=true"
              method="POST"
              action="{{ route('donaciones.store', $cause->slug) }}"
              class="mt-6 space-y-4">
          @csrf

          <input type="hidden" name="client_ref" value="{{ $clientRef }}">
        <div>
          <label class="text-sm text-slate-200">Cantidad (MXN)</label>

        
          <input name="amount_mxn" type="number" min="1" step="0.01" value="{{ old('amount_mxn') }}"
                placeholder="Ej. 200"
                class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400" />
          @error('amount_mxn') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
          <div class="mt-1 text-xs text-slate-400">*Sin mínimo (lo validaremos en backend después).</div>
        </div>

        <div>
          <label class="text-sm text-slate-200">Mensaje (opcional)</label>
          <textarea name="message" rows="4"
              placeholder="Ej. Espero que ayude mucho"
              class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400">{{ old('message') }}</textarea>
            @error('message') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
        </div>

        <div class="grid gap-3">
          

          <button type="submit"
            formaction="{{ route('mp.start', $cause->slug) }}"
            :disabled="submitting"
            class="w-full rounded-xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white hover:bg-sky-400 disabled:opacity-60 disabled:cursor-not-allowed">
            Donar con Mercado Pago
          </button>
          <button type="submit"
            formaction="{{ route('paypal.start', $cause->slug) }}"
            :disabled="submitting"
            class="w-full rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold hover:bg-white/15 disabled:opacity-60 disabled:cursor-not-allowed">
            Donar con PayPal
          </button>
        </div>
      </form>
    </section>

    <aside class="rounded-3xl border border-white/10 bg-white/5 p-8">
      <div class="text-sm font-semibold">Resumen</div>
      <div class="mt-4 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
        @php
          $slug = $cause->slug;
          $base = "images/causes/{$slug}/cover";
          $extensions = ['png','jpg','jpeg','webp'];

          $coverUrl = null;
          foreach ($extensions as $ext) {
            $try = "{$base}.{$ext}";
            if (file_exists(public_path($try))) {
              $coverUrl = asset($try);
              break;
            }
          }
          if (!$coverUrl) $coverUrl = asset('images/causes/placeholder.png');
        @endphp

        <div class="mb-5 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
          <img src="{{ $coverUrl }}"
              alt="{{ $cause->title }}"
              class="h-56 w-full object-cover md:h-64" />
        </div>
      </div>

      <div class="mt-4 text-sm text-slate-300">
        Tu donativo se usará para apoyar esta causa. Agradecemos cualquier apoyo para su realización.
      </div>
    </aside>
  </div>
@endsection