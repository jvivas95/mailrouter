{{-- Stats --}}
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
