{{-- Stats --}}
<main class="flex-1 px-8 py-8">
    <div id="overview" class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total recibidos</p>
            <p id="stat-total" class="text-3xl font-bold text-indigo-400 mono">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Reenviados</p>
            <p id="stat-forwarded" class="text-3xl font-bold text-green-400 mono">{{ $stats['forwarded'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pendientes</p>
            <p id="stat-pending" class="text-3xl font-bold text-yellow-400 mono">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Errores</p>
            <p id="stat-errors" class="text-3xl font-bold text-red-400 mono">{{ $stats['errors'] }}</p>
        </div>
    </div>

    {{-- Tabla de correos --}}
    <div id="emails-section" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-white">✉ Correos recibidos</h2>
        <span class="text-xs text-gray-500 mono">Últimos {{ $emails->count() }}</span>
        </div>

        @if($emails->isNotEmpty())
        <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
            <tr class="border-b border-gray-800">
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Remitente</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Asunto</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Reenviado a</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Fecha</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($emails as $email)
            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">

                <td class="py-3 px-6 text-sm text-white max-w-[180px] truncate">
                {{ $email->sender }}
                </td>

                <td class="py-3 px-6 text-sm text-gray-400 max-w-[220px] truncate">
                {{ $email->subject }}
                </td>

                <td class="py-3 px-6 text-xs text-gray-500 max-w-[160px] truncate">
                {{ $email->forwarded_to ?? '—' }}
                </td>

                <td class="py-3 px-6 text-xs text-gray-500 mono whitespace-nowrap">
                {{ $email->created_at?->format('d/m/Y H:i') ?? '—' }}
                </td>

                <td class="py-3 px-6">
                @if($email->status === 'forwarded')
                    <span class="px-2 py-1 rounded text-xs mono bg-green-900/40 text-green-400">✓ enviado</span>
                @elseif($email->status === 'error')
                    <span class="px-2 py-1 rounded text-xs mono bg-red-900/40 text-red-400">✕ error</span>
                @elseif($email->status === 'pending')
                    <span class="px-2 py-1 rounded text-xs mono bg-yellow-900/40 text-yellow-400">⏳ pendiente</span>
                @else
                    <span class="px-2 py-1 rounded text-xs mono bg-gray-800 text-gray-500">— sin dest.</span>
                @endif
                </td>

                <td class="py-3 px-4">
                <a href="/emails/{{ $email->id }}"
                    class="px-2 py-1 text-xs bg-gray-800 border border-gray-700 text-gray-400
                            rounded hover:bg-gray-700 transition-colors">
                    ver
                </a>
                </td>

            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @else
        <div class="py-16 text-center">
        <p class="text-4xl mb-3 opacity-30">📭</p>
        <p class="text-sm text-gray-500">No hay correos registrados aún</p>
        </div>
        @endif
    </div>
</main>
