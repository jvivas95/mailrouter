{{-- Topbar --}}
<header class="sticky top-0 z-40 border-b border-gray-800 bg-gray-900 px-4 py-3 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-3">
        <div class="flex min-w-0 items-center gap-3">
            {{-- Botón hamburguesa — solo visible en móvil --}}
            <button onclick="toggleSidebar()"
                    class="lg:hidden rounded-lg p-2 text-gray-400 transition-colors hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="truncate text-sm font-semibold text-white mono sm:text-base">📨 MailRouter</h1>
        </div>

        @if(auth()->user()->isAdmin())
            <div class="flex flex-wrap items-center justify-end gap-2">

            {{-- Revisar ahora --}}
            <form method="POST" action="/worker/check-now">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-gray-700 bg-gray-800 px-3 py-1.5 text-xs text-gray-300 transition-colors hover:bg-gray-700 sm:text-sm">
                ↻ Revisar ahora
                </button>
            </form>

            {{-- Iniciar / Detener --}}
            @if($config['active'] ?? false)
            <form method="POST" action="/worker/stop">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-red-800/50 bg-red-900/30 px-3 py-1.5 text-xs text-red-400 transition-colors hover:bg-red-900/50 sm:text-sm">
                ⏹ Detener monitor
                </button>
            </form>
            @else
            <form method="POST" action="/worker/start">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-green-800/50 bg-green-900/30 px-3 py-1.5 text-xs text-green-400 transition-colors hover:bg-green-900/50 sm:text-sm">
                ▶ Iniciar monitor
                </button>
            </form>
            @endif

            </div>
        @endif
    </div>
</header>
