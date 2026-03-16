@extends('layouts.main')

@section('content')
@php
  // ===== IMÁGENES DINÁMICAS POR CAUSA =====
  $slug = $cause->slug;
  $folder = "images/causes/{$slug}";
  $extensions = ['png','jpg','jpeg','webp'];

  // Cover (cover.png / cover.jpg / etc.)
  $coverUrl = null;
  foreach ($extensions as $ext) {
    $try = "{$folder}/cover.{$ext}";
    if (file_exists(public_path($try))) {
      $coverUrl = asset($try);
      break;
    }
  }
  if (!$coverUrl) $coverUrl = asset('images/causes/placeholder.png');

  // Galería (1.png, 2.png, 3.png..., cualquier nombre, excluye cover.*)
  $files = is_dir(public_path($folder))
      ? glob(public_path($folder).'/*.{png,jpg,jpeg,webp}', GLOB_BRACE)
      : [];

  // Convertir a URLs y filtrar cover
  $gallery = collect($files)
      ->map(function($fullPath) {
          $relative = str_replace(public_path().DIRECTORY_SEPARATOR, '', $fullPath);
          $relative = str_replace('\\', '/', $relative);
          return asset($relative);
      })
      ->filter(fn($url) => !preg_match('/\/cover\.(png|jpg|jpeg|webp)$/i', $url))
      ->values();

  // Ordenar por nombre natural (1,2,3,10...)
  $gallery = $gallery->sortBy(function($url) {
      return basename(parse_url($url, PHP_URL_PATH));
  }, SORT_NATURAL)->values();

  // ===== TEXTOS (usa lo que tengas en BD; si no existe, pone placeholders) =====
  $problem = $cause->problem_description ?? null;
  $since = $cause->since_date ? \Illuminate\Support\Carbon::parse($cause->since_date)->format('d/m/Y') : null;
  $funds = $cause->use_of_funds ?? null;
  $impact = $cause->impact ?? null;
@endphp

<div class="flex flex-col gap-6">
  {{-- Header / Breadcrumb --}}
  <div class="flex items-center justify-between gap-4">
    <div>
      <a href="{{ route('causas.index') }}" class="text-sm text-slate-300 hover:text-slate-100">
        ← Volver a Causas
      </a>
      <h1 class="mt-2 text-2xl font-bold">{{ $cause->title }}</h1>
      <p class="mt-2 text-slate-300">
        {{ $cause->short_description }}
      </p>
    </div>

    <div class="hidden md:flex">
      <a href="{{ route('donaciones.create', $cause->slug) }}"
         class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400">
        Donar ahora
      </a>
    </div>
  </div>

  {{-- Cover --}}
  <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
    <img src="{{ $coverUrl }}"
         alt="{{ $cause->title }}"
         class="h-[260px] w-full object-cover md:h-[360px]" />
    <div class="p-5 md:p-6">
      <div class="flex flex-wrap items-center justify-between gap-3">
        

        <a href="{{ route('donaciones.create', $cause->slug) }}"
           class="md:hidden rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400">
          Donar ahora
        </a>
      </div>
    </div>
  </div>

  {{-- Galería --}}
  <div class="rounded-3xl border border-white/10 bg-white/5 p-5 md:p-6">
    <div class="flex items-center justify-between">
      <div class="text-sm font-semibold">Galería</div>
      
    </div>

    @if($gallery->isEmpty())
      
    @else
      <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($gallery as $img)
          <a href="{{ $img }}" target="_blank" class="group overflow-hidden rounded-2xl border border-white/10 bg-slate-950/30">
            <img src="{{ $img }}"
                 alt="Imagen {{ $loop->iteration }}"
                 class="h-44 w-full object-cover transition duration-300 group-hover:scale-105" />
          </a>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Secciones de contenido --}}
  <div class="grid gap-4 md:grid-cols-2">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <div class="text-sm font-semibold">La problemática</div>
      <p class="mt-3 text-sm text-slate-300">
        {{ $problem ?? 'Aquí describe la problemática con detalle (puedes dejarlo fijo o luego guardarlo en BD). Explica por qué es importante apoyar esta causa, a quién afecta y qué consecuencias tiene.' }}
      </p>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <div class="text-sm font-semibold">¿Desde cuándo está esta causa?</div>
      <p class="mt-3 text-sm text-slate-300">
        {{ $since ?? 'Ejemplo: Desde Enero 2024. (Puedes cambiar este texto o agregar un campo en BD para manejarlo dinámico).' }}
      </p>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 md:col-span-2">
      <div class="text-sm font-semibold">¿Qué se hará con el dinero recaudado?</div>
      <p class="mt-3 text-sm text-slate-300">
        {!! nl2br(e($funds)) !!}
      </p>

      <div class="mt-4 grid gap-3 md:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
          <div class="text-xs text-slate-400">Transparencia</div>
          <div class="mt-1 text-sm text-slate-200">Uso responsable del donativo</div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
          <div class="text-xs text-slate-400">Impacto</div>
          <div class="mt-1 text-sm text-slate-200">Apoyo directo a la comunidad</div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
          <div class="text-xs text-slate-400">Seguimiento</div>
          <div class="mt-1 text-sm text-slate-200">Registro en historial del usuario</div>
        </div>
      </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 md:col-span-2">
      <div class="text-sm font-semibold">Impacto esperado</div>
      <p class="mt-3 text-sm text-slate-300">
        {!! nl2br(e($impact))!!}
      </p>
    </div>
  </div>

  {{-- CTA final --}}
  <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-500/15 via-white/5 to-emerald-500/10 p-6">
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
      <div>
        <div class="text-lg font-semibold">¿Listo para apoyar esta causa?</div>
        <div class="mt-1 text-sm text-slate-300">
          Tu donativo puede marcar una diferencia real. Gracias por tu apoyo ❤️
        </div>
      </div>

      <a href="{{ route('donaciones.create', $cause->slug) }}"
         class="w-full md:w-auto rounded-xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-400 text-center">
        Donar
      </a>
    </div>
  </div>
</div>
@endsection