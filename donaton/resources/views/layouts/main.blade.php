<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ config('app.name', 'DONATON') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-100">
  {{-- NAVBAR --}}
  <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
      <a href="{{ route('landing') }}" class="flex items-center gap-3">
        {{-- Placeholder logo --}}
        <div class="h-9 w-9 rounded-xl bg-white/10 ring-1 ring-white/15"></div>
        <div class="leading-tight">
          <div class="font-semibold tracking-wide">DONATON</div>
          <div class="text-xs text-slate-300">Plataforma de donativos</div>
        </div>
      </a>

      <div class="flex items-center gap-2">
        <a href="{{ route('contacto.form') }}"
           class="rounded-lg px-3 py-2 text-sm text-slate-200 hover:bg-white/10">
          Contacto
        </a>

        @auth
          <a href="{{ route('causas.index') }}"
             class="rounded-lg px-3 py-2 text-sm text-slate-200 hover:bg-white/10">
            Causas
          </a>

          <a href="#"
             class="rounded-lg px-3 py-2 text-sm text-slate-200 hover:bg-white/10">
            Mis donativos
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
              class="rounded-lg bg-white/10 px-3 py-2 text-sm hover:bg-white/15">
              Salir
            </button>
          </form>
        @else
          <a href="{{ route('login') }}"
             class="rounded-lg px-3 py-2 text-sm text-slate-200 hover:bg-white/10">
            Iniciar sesión
          </a>
          <a href="{{ route('register') }}"
             class="rounded-lg bg-indigo-500 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-400">
            Registrarme
          </a>
        @endauth
      </div>
    </nav>
  </header>

  {{-- FLASH MESSAGES --}}
  <div class="mx-auto max-w-6xl px-4 pt-6">
    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-emerald-100">
        {{ session('success') }}
      </div>
    @endif
    @if (session('error'))
      <div class="mb-4 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-rose-100">
        {{ session('error') }}
      </div>
    @endif
  </div>

  {{-- CONTENT --}}
  <main class="mx-auto max-w-6xl px-4 pb-16 pt-6">
    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer class="border-t border-white/10">
    <div class="mx-auto grid max-w-6xl grid-cols-1 gap-6 px-4 py-10 md:grid-cols-3">
      <div></div>

      <div class="flex items-center justify-center gap-3">
        <div class="h-10 w-10 rounded-2xl bg-white/10 ring-1 ring-white/15"></div>
        <div class="text-center">
          <div class="font-semibold">DONATON</div>
          <div class="text-xs text-slate-300">Gracias por apoyar ❤️</div>
        </div>
      </div>

      <div class="text-center md:text-right">
        <div class="text-sm text-slate-200">Contacto</div>
        <div class="text-xs text-slate-400">correo@ejemplo.com</div>
        <div class="text-xs text-slate-400">Monterrey, MX</div>
      </div>
    </div>
  </footer>
</body>
</html>