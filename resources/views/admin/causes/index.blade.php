@extends('layouts.main')

@section('content')
  <div class="flex items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold">Admin - Causas</h1>
      <p class="mt-2 text-slate-300">Edita los textos que aparecen en el detalle de cada causa.</p>
    </div>

    <a href="{{ route('admin.dashboard') }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold hover:bg-white/15">
      Volver al Dashboard
    </a>
  </div>

  <div class="mt-6 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
    <div class="p-6 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-slate-300">
          <tr class="border-b border-white/10">
            <th class="py-2 text-left">Causa</th>
            <th class="py-2 text-left">Slug</th>
            <th class="py-2 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($causes as $c)
            <tr class="border-b border-white/5">
              <td class="py-2">{{ $c->title }}</td>
              <td class="py-2 text-slate-400">{{ $c->slug }}</td>
              <td class="py-2 text-right">
                <a href="{{ route('admin.causes.edit', $c) }}"
                   class="rounded-lg bg-indigo-500 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-400">
                  Editar
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection