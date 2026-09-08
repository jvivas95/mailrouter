{{-- Emails View --}}
<main class="max-w-full flex-1 overflow-hidden px-3 py-4 sm:px-5 lg:px-8 lg:py-8">

    {{-- Overview Stats --}}
    <div id="overview" class="grid grid-cols-1 gap-3 p-1 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4 lg:p-6">
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
    <div id="emails-section" class="w-full overflow-hidden rounded-xl border border-gray-800 bg-gray-900 mt-4">
        {{-- Section Header --}}
        <div class="flex items-center justify-between gap-3 border-b border-gray-800 px-3 py-3 sm:px-5 lg:px-6">
            <h2 class="text-sm font-semibold text-white">✉ Correos recibidos</h2>
            <span class="text-[10px] text-gray-500 mono sm:text-xs">Últimos {{ $emails->count() }}</span>
        </div>
        {{-- Filters --}}
        <div x-data="{ showFilters: false }" class="border-b border-gray-800">
            {{-- Filter Toggle Button --}}
            <button
                @click="showFilters = !showFilters"
                type="button"
                class="flex w-full items-center justify-between bg-gray-900/70 px-3 py-3 transition-colors hover:bg-gray-800/50 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:ring-offset-2 focus:ring-offset-gray-900 sm:px-5 lg:px-6">
                <span x-text="showFilters ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                <svg x-show="!showFilters" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="showFilters" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <div x-show="showFilters" x-collapse class="border-b border-gray-800 bg-gray-900/70 px-3 py-4 sm:px-5 lg:px-6">
                <form method="GET" action="/emails" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5 xl:items-end">
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

        {{-- Emails List --}}
        <div class="overflow-x-hidden">
            {{-- Desktop table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full min-w-0 table-auto border-collapse">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Remitente</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Asunto</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reenviado a</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Fecha</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                            <th class="w-12 py-3 px-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emails as $email)
                            <tr class="border-b border-gray-800/50 transition-colors hover:bg-gray-800/30">
                                <td class="max-w-[180px] overflow-hidden text-ellipsis whitespace-nowrap py-3 px-6 text-sm text-white">
                                    {{ $email->sender }}
                                </td>
                                <td class="max-w-[220px] overflow-hidden text-ellipsis whitespace-nowrap py-3 px-6 text-sm text-gray-400">
                                    {{ $email->subject }}
                                </td>
                                <td class="max-w-[160px] overflow-hidden text-ellipsis whitespace-nowrap py-3 px-6 text-xs text-gray-500">
                                    {{ $email->forwarded_to ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap py-3 px-6 text-xs text-gray-500 mono">
                                    {{ $email->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="py-3 px-6">
                                    @if($email->status === 'forwarded')
                                        <span class="rounded bg-green-900/40 px-2 py-1 text-xs mono text-green-400">✓ enviado</span>
                                    @elseif($email->status === 'error')
                                        <span class="rounded bg-red-900/40 px-2 py-1 text-xs mono text-red-400">✕ error</span>
                                    @elseif($email->status === 'pending')
                                        <span class="rounded bg-yellow-900/40 px-2 py-1 text-xs mono text-yellow-400">⏳ pendiente</span>
                                    @else
                                        <span class="rounded bg-gray-800 px-2 py-1 text-xs mono text-gray-500">— sin dest.</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <a href="/emails/{{ $email->id }}"
                                        class="inline-flex rounded border border-gray-700 bg-gray-800 px-2 py-1 text-xs text-gray-400 transition-colors hover:bg-gray-700">
                                        ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <p class="mb-3 text-4xl opacity-30">📭</p>
                                    <p class="text-sm text-gray-500">No hay correos registrados aún</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="space-y-3 p-3 md:hidden">
                @forelse($emails as $email)
                    <article class="rounded-xl border border-gray-800 bg-gray-950/50 p-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-500">Remitente</p>
                                <p class="mt-1 break-words text-sm font-medium text-white">{{ $email->sender }}</p>
                            </div>
                            @if($email->status === 'forwarded')
                                <span class="rounded bg-green-900/40 px-2 py-1 text-[10px] mono text-green-400">✓ enviado</span>
                            @elseif($email->status === 'error')
                                <span class="rounded bg-red-900/40 px-2 py-1 text-[10px] mono text-red-400">✕ error</span>
                            @elseif($email->status === 'pending')
                                <span class="rounded bg-yellow-900/40 px-2 py-1 text-[10px] mono text-yellow-400">⏳ pendiente</span>
                            @else
                                <span class="rounded bg-gray-800 px-2 py-1 text-[10px] mono text-gray-500">— sin dest.</span>
                            @endif
                        </div>

                        <div class="mt-3 space-y-2 text-xs text-gray-300">
                            <div>
                                <span class="text-gray-500">Asunto:</span>
                                <p class="mt-1 break-words text-gray-200">{{ $email->subject }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Reenviado a:</span>
                                <p class="mt-1 break-words">{{ $email->forwarded_to ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Fecha:</span>
                                <p class="mt-1 mono text-gray-400">{{ $email->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <a href="/emails/{{ $email->id }}"
                                class="inline-flex rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-xs font-medium text-gray-200 transition-colors hover:bg-gray-700">
                                Ver detalle
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-700 bg-gray-950/30 p-8 text-center md:hidden">
                        <p class="mb-3 text-4xl opacity-30">📭</p>
                        <p class="text-sm text-gray-500">No hay correos registrados aún</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="border-t border-gray-800 bg-gray-900/70 px-3 py-4 sm:px-5 lg:px-6">
                {{ $emails->links() }}
            </div>
        </div>
    </div>
</main>
