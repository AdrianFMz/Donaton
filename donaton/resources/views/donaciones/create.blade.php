@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Donar a: {{ $cause->title }}</h1>
  <p class="mt-2 text-slate-300">Elige una cantidad (MXN) y deja un mensaje opcional.</p>

  <div class="mt-6 grid gap-6 md:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
      <form class="space-y-4">
        {{-- Sprint 0: solo UI. En Sprint 2/3 conectamos POST + MercadoPago --}}
        <div>
          <label class="text-sm text-slate-200">Cantidad (MXN)</label>
          <input type="number" min="1" step="1"
                 placeholder="Ej. 200"
                 class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400" />
          <div class="mt-1 text-xs text-slate-400">*Sin mínimo (lo validaremos en backend después).</div>
        </div>

        <div>
          <label class="text-sm text-slate-200">Mensaje (opcional)</label>
          <textarea rows="4"
            placeholder="Ej. Espero que ayude mucho ❤️"
            class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3 outline-none focus:border-indigo-400"></textarea>
        </div>

        <div class="grid gap-3">
          <button type="button"
            class="w-full rounded-xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white hover:bg-sky-400">
            Donar con Mercado Pago (próximamente)
          </button>
          <button type="button"
            class="w-full rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold hover:bg-white/15">
            Donar con PayPal (próximamente)
          </button>
        </div>
      </form>
    </section>

    <aside class="rounded-3xl border border-white/10 bg-white/5 p-8">
      <div class="text-sm font-semibold">Resumen</div>
      <div class="mt-4 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
        <div class="aspect-video bg-white/5"></div>
        <div class="p-3 text-xs text-slate-300">*Imagen de la causa</div>
      </div>

      <div class="mt-4 text-sm text-slate-300">
        Tu donativo se usará para apoyar esta causa. En Sprint 3 haremos la confirmación real del pago.
      </div>
    </aside>
  </div>
@endsection