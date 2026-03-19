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
    <nav x-data="{ open:false, userOpen:false }" class="mx-auto max-w-6xl px-4 py-3">
      <div class="flex items-center justify-between gap-3">
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
          <img src="{{ asset('images/ui/logo.png') }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-white/15" alt="DONATON">
          <div class="leading-tight">
            <div class="font-semibold tracking-wide">DONATON</div>
            <div class="text-xs text-slate-300">Plataforma de donativos</div>
          </div>
        </a>

        {{-- Desktop links --}}
        <div class="hidden items-center gap-2 md:flex">
          <a href="{{ route('contacto.form') }}"
             class="rounded-lg px-3 py-2 text-sm hover:bg-white/10 {{ request()->routeIs('contacto.*') ? 'bg-white/10' : '' }}">
            Contacto
          </a>

          @auth
            <a href="{{ route('causas.index') }}"
               class="rounded-lg px-3 py-2 text-sm hover:bg-white/10 {{ request()->routeIs('causas.*') ? 'bg-white/10' : '' }}">
              Causas
            </a>

            <a href="{{ route('donaciones.mine') }}"
               class="rounded-lg px-3 py-2 text-sm hover:bg-white/10 {{ request()->routeIs('donaciones.mine') ? 'bg-white/10' : '' }}">
              Mis donativos
            </a>

            @if(auth()->user()?->role === 'admin')
              <a href="{{ route('admin.dashboard') }}"
                 class="rounded-lg px-3 py-2 text-sm hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
                Dashboard
              </a>
            @endif

            {{-- User dropdown (derecha) --}}
            <div class="relative" @click.away="userOpen=false">
              <button @click="userOpen = !userOpen"
                      class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-left hover:bg-white/15">
                <div class="flex flex-col leading-tight">
                  <span class="text-sm font-medium text-slate-100">{{ Auth::user()->name }}</span>
                  <span class="text-xs text-slate-300">{{ Auth::user()->email }}</span>
                </div>
                <svg class="h-4 w-4 text-slate-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>

              <div x-show="userOpen" x-cloak
                   class="absolute right-0 mt-2 w-56 overflow-hidden rounded-xl border border-white/10 bg-slate-950/95 shadow-xl">
                <a href="{{ route('donaciones.mine') }}"
                   class="block px-4 py-3 text-sm text-slate-200 hover:bg-white/10">
                  Mis donativos
                </a>

                @if(auth()->user()?->role === 'admin')
                  <a href="{{ route('admin.dashboard') }}"
                     class="block px-4 py-3 text-sm text-slate-200 hover:bg-white/10">
                    Dashboard Admin
                  </a>

                  <a href="{{ route('admin.causes.index') }}"
                    class="rounded-lg px-3 py-2 text-sm hover:bg-white/10 {{ request()->routeIs('admin.causes.*') ? 'bg-white/10' : '' }}">
                    Admin Causas
                  </a>

                @endif

                
                  
                

                <div class="border-t border-white/10"></div>

                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                    class="w-full px-4 py-3 text-left text-sm text-slate-200 hover:bg-white/10">
                    Cerrar sesión
                  </button>
                </form>
              </div>
            </div>
          @else
            <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-white/10">
              Iniciar sesión
            </a>
            <a href="{{ route('register') }}"
               class="rounded-lg bg-indigo-500 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-400">
              Registrarme
            </a>
          @endauth
        </div>

        {{-- Mobile button --}}
        <button @click="open = !open"
                class="inline-flex items-center justify-center rounded-lg bg-white/10 px-3 py-2 text-sm hover:bg-white/15 md:hidden">
          Menú
        </button>
      </div>

      {{-- Mobile menu --}}
      <div x-show="open" x-cloak class="mt-3 space-y-2 md:hidden">
        <a href="{{ route('contacto.form') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10">
          Contacto
        </a>

        @auth
          <a href="{{ route('causas.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10">
            Causas
          </a>
          <a href="{{ route('donaciones.mine') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10">
            Mis donativos
          </a>

          @if(auth()->user()?->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10">
              Dashboard
            </a>

            <a href="{{ route('admin.causes.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10 {{ request()->routeIs('admin.causes.*') ? 'bg-white/10' : '' }}">
              Admin Causas
            </a>
          @endif

          
            
          

          <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2">
            <div class="text-sm font-medium text-slate-100">{{ Auth::user()->name }}</div>
            <div class="text-xs text-slate-300">{{ Auth::user()->email }}</div>
          </div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full rounded-lg bg-white/10 px-3 py-2 text-left text-sm hover:bg-white/15">
              Cerrar sesión
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10">
            Iniciar sesión
          </a>
          <a href="{{ route('register') }}" class="block rounded-lg bg-indigo-500 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-400">
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
        <img src="{{ asset('images/ui/logo.png') }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-white/15" alt="DONATON">
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