{{-- Emails View --}}
<main class="flex-1 px-8 py-8">

    {{-- Overview Stats --}}
    <div id="overview" class="grid grid-cols-4 gap-4 mb-8">
        {{-- Total Received --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total recibidos</p>
            <p id="stat-total" class="text-3xl font-bold text-indigo-400 mono">{{ $stats['total'] }}</p>
        </div>
        {{-- Total Forwarded --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Reenviados</p>
            <p id="stat-forwarded" class="text-3xl font-bold text-green-400 mono">{{ $stats['forwarded'] }}</p>
        </div>
        {{-- Total Pending --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pendientes</p>
            <p id="stat-pending" class="text-3xl font-bold text-yellow-400 mono">{{ $stats['pending'] }}</p>
        </div>
        {{-- Total Errors --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Errores</p>
            <p id="stat-errors" class="text-3xl font-bold text-red-400 mono">{{ $stats['errors'] }}</p>
        </div>
    </div>

    {{-- Emails received --}}
    <div id="emails-section" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        {{-- Section Header --}}
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-white">✉ Correos recibidos</h2>
            <span class="text-xs text-gray-500 mono">Últimos {{ $emails->count() }}</span>
        </div>
        {{-- Filters --}}
        <div x-data="{ showFilters: false }" class="border-b border-gray-800">
            {{-- Filter Toggle Button --}}
            <button
                @click="showFilters = !showFilters"
                type="button"
                class="w-full flex items-center justify-between px-6 py-4 bg-gray-900/70 hover:bg-gray-800/50 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:ring-offset-2 focus:ring-offset-gray-900">
                <span x-text="showFilters ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                <svg x-show="!showFilters" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="showFilters" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <div x-show="showFilters" x-collapse class="px-6 py-5 border-b border-gray-800 bg-gray-900/70">
                <form method="GET" action="/emails" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
                    {{-- Sender --}}
                    <div class="min-w-0">
                        <label for="sender" class="block text-xs font-medium text-gray-400 mb-2">Remitente</label>
                        <input type="email" name="sender" id="sender" placeholder="correo@ejemplo.com" value="{{ request('sender') }}"
                            class="w-full px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-colors"
                        >
                    </div>
                    {{-- Subject --}}
                    <div class="min-w-0">
                        <label for="subject" class="block text-xs font-medium text-gray-400 mb-2">Asunto</label>
                        <input type="text" name="subject" id="subject" placeholder="Buscar por asunto" value="{{ request('subject') }}"
                            class="w-full px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-colors"
                        >
                    </div>
                    {{-- Recipient/forwarded_to --}}
                    <div class="min-w-0">
                        <label for="forwarded_to" class="block text-xs font-medium text-gray-400 mb-2">Usuario</label>
                        <select name="forwarded_to" id="forwarded_to" class="w-full px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-colors">
                            <option value="">Todos los usuarios</option>
                            @foreach ($active as $recipient)
                                <option value="{{ $recipient->email }}" {{ request('forwarded_to') == $recipient->email ? 'selected' : '' }}>
                                    {{ $recipient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Email status --}}
                    <div class="min-w-0">
                        <label for="status" class="block text-xs font-medium text-gray-400 mb-2">Estado</label>
                        <select name="status" id="status" class="w-full px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-colors">
                            <option value="">Todos los estados</option>
                            <option value="forwarded" {{ request('status') == 'forwarded' ? 'selected' : '' }}>Enviado</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>Error</option>
                        </select>
                    </div>
                    {{-- Date From --}}
                    <div class="min-w-0">
                        <label for="from_date" class="block text-xs font-medium text-gray-400 mb-2">Desde</label>
                        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-colors">
                    </div>
                    {{-- Date To --}}
                    <div class="min-w-0">
                        <label for="to_date" class="block text-xs font-medium text-gray-400 mb-2">Hasta</label>
                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-colors">
                    </div>
                    {{-- Buttons --}}
                    <div class="sm:col-span-2 xl:col-span-5 flex flex-col sm:flex-row sm:justify-end gap-3 pt-1">
                        <a href="{{ route('emails.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-800 border border-gray-700 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors">
                            Limpiar filtros
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-sm font-medium text-white rounded-lg hover:bg-indigo-500 transition-colors">
                            Aplicar filtros
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Emails Table --}}
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
                    @forelse($emails as $email)
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
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <p class="text-4xl mb-3 opacity-30">📭</p>
                                <p class="text-sm text-gray-500">No hay correos registrados aún</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/70">
                {{ $emails->links() }}
            </div>
        </div>
    </div>
</main>
