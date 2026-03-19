@extends('layouts.main')

@section('content')
  <h1 class="text-2xl font-bold">Causas a apoyar</h1>
  <p class="mt-2 text-slate-300">Elige una causa y conoce su historia.</p>

  <div class="mt-6 grid gap-4 md:grid-cols-3">
    @foreach($causes as $cause)
        <a href="{{ route('causas.show', $cause->slug) }}"
          class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.03] hover:border-indigo-400/50">
        <div class="absolute inset-0 opacity-0 transition group-hover:opacity-100">
          <div class="h-full w-full bg-gradient-to-br from-indigo-500/15 via-white/5 to-emerald-500/10"></div>
        </div>

        <div class="relative">
          <div class="mb-4 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
            @php
              $base = "images/causes/{$cause->slug}/cover";
              $extensions = ['png','jpg','jpeg','webp'];

              $coverUrl = null;
              foreach ($extensions as $ext) {
                  $tryPath = "{$base}.{$ext}";
                  if (file_exists(public_path($tryPath))) {
                      $coverUrl = asset($tryPath);
                      break;
                  }
              }

              // fallback
              if (!$coverUrl) {
                  $coverUrl = asset('images/causes/placeholder.png');
              }
            @endphp

            <img src="{{ $coverUrl }}"
                alt="{{ $cause->title }}"
                class="aspect-video w-full rounded-2xl object-cover transition duration-300 group-hover:scale-105 group-hover:opacity-90 ring-1 ring-white/10" />
          </div>

          <h2 class="text-lg font-semibold">{{ $cause->title }}</h2>
          <p class="mt-2 text-sm text-slate-300 line-clamp-3">{{ $cause->short_description }}</p>

          <div class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-indigo-300">
            Ver detalles <span class="transition group-hover:translate-x-1">→</span>
          </div>
        </div>
      </a>
    @endforeach
  </div>
@endsection