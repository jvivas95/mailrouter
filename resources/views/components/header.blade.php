{{-- Topbar --}}
<header class="sticky top-0 z-40 bg-gray-900 border-b border-gray-800 px-8 py-4 flex items-center justify-between">
    <h1 class="text-base font-semibold text-white mono">📨 MailRouter</h1>

    @if(auth()->user()->isAdmin())
        <div class="flex items-center gap-2">

        {{-- Revisar ahora --}}
        <form method="POST" action="/worker/check-now">
            @csrf
            <button type="submit"
                    class="px-3 py-1.5 text-sm bg-gray-800 border border-gray-700 text-gray-300
                            rounded-lg hover:bg-gray-700 transition-colors">
            ↻ Revisar ahora
            </button>
        </form>

        {{-- Iniciar / Detener --}}
        @if($config['active'] ?? false)
        <form method="POST" action="/worker/stop">
            @csrf
            <button type="submit"
                    class="px-3 py-1.5 text-sm bg-red-900/30 border border-red-800/50 text-red-400
                            rounded-lg hover:bg-red-900/50 transition-colors">
            ⏹ Detener monitor
            </button>
        </form>
        @else
        <form method="POST" action="/worker/start">
            @csrf
            <button type="submit"
                    class="px-3 py-1.5 text-sm bg-green-900/30 border border-green-800/50 text-green-400
                            rounded-lg hover:bg-green-900/50 transition-colors">
            ▶ Iniciar monitor
            </button>
        </form>
        @endif

        </div>
    @endif
</header>
