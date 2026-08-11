{{-- resources/views/partials/recipients.blade.php --}}
<div id="recipients-section" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">

  {{-- Header --}}
  <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
    <h2 class="text-sm font-semibold text-white">👥 Departamento comercial</h2>
    <span class="text-xs text-gray-500 mono">{{ $recipients->where('active', true)->count() }} activos</span>
  </div>

  {{-- Siguiente en recibir --}}
  @if(isset($currentRecipient) && $currentRecipient)
  <div class="px-5 pt-4">
    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Siguiente en recibir</p>
    <div class="flex items-center gap-3 p-3 bg-indigo-900/10 border border-indigo-800/30 rounded-lg mb-4">
        <span class="relative flex h-2 w-2 flex-shrink-0">...</span>
        <div>
            <p id="next-recipient-name" class="text-sm font-semibold text-indigo-300">{{ $currentRecipient->name }}</p>
            <p id="next-recipient-email" class="text-xs text-gray-500">{{ $currentRecipient->email }}</p>
        </div>
    </div>
  </div>
  @endif

  {{-- Lista drag and drop --}}
  <ul id="recipients-list" class="px-5 divide-y divide-gray-800/60">
    @forelse($recipients as $recipient)
    <li data-id="{{ $recipient->id }}"
        class="flex items-center gap-3 py-3 {{ !$recipient->active ? 'opacity-40' : '' }}
               {{ auth()->user()->isAdmin() ? 'cursor-grab active:cursor-grabbing' : '' }}">

      {{-- Handle drag — solo admin --}}
      @if(auth()->user()->isAdmin())
      <span class="text-gray-600 select-none text-base drag-handle" title="Arrastra para reordenar">⠿</span>
      @endif

      {{-- Avatar --}}
      <div class="w-8 h-8 rounded-full bg-indigo-700 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
        {{ strtoupper(substr($recipient->name, 0, 1)) }}
      </div>

      {{-- Info --}}
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-white truncate">{{ $recipient->name }}</p>
        <p class="text-xs text-gray-500 truncate">{{ $recipient->email }}</p>
      </div>

      {{-- Badge NEXT --}}
      @if(isset($currentRecipient) && $recipient->id === $currentRecipient->id && $recipient->active)
      <span class="text-xs font-semibold text-indigo-400 bg-indigo-900/30 border border-indigo-800/50 px-1.5 py-0.5 rounded flex-shrink-0">
        NEXT
      </span>
      @endif

      {{-- Acciones admin --}}
      @if(auth()->user()->isAdmin())
      <div class="flex items-center gap-1 flex-shrink-0">
        <form method="POST" action="/recipients/{{ $recipient->id }}/toggle">
          @csrf @method('PATCH')
          <button type="submit"
                  class="p-1.5 text-xs bg-gray-800 border border-gray-700 rounded hover:bg-gray-700 transition-colors"
                  title="{{ $recipient->active ? 'Desactivar' : 'Activar' }}">
            {{ $recipient->active ? '👁' : '🚫' }}
          </button>
        </form>
        <form method="POST" action="/recipients/{{ $recipient->id }}"
              onsubmit="return confirm('¿Eliminar a {{ $recipient->name }}?')">
          @csrf @method('DELETE')
          <button type="submit"
                  class="p-1.5 text-xs bg-red-900/20 border border-red-800/40 text-red-400 rounded hover:bg-red-900/40 transition-colors">
            ✕
          </button>
        </form>
      </div>
      @endif

    </li>
    @empty
    <li class="py-10 text-center">
      <p class="text-3xl mb-2 opacity-30">👥</p>
      <p class="text-xs text-gray-500">Sin destinatarios aún</p>
    </li>
    @endforelse
  </ul>

  {{-- Formulario añadir — solo admin --}}
  @if(auth()->user()->isAdmin())
  <div class="px-5 py-4 border-t border-gray-800 bg-gray-950/40">
    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Agregar destinatario</p>
    <form method="POST" action="/recipients" class="space-y-2">
      @csrf
      <input type="text" name="name" placeholder="Ana García" required
             class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                    placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
      <input type="email" name="email" placeholder="ana@empresa.com" required
             class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                    placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
      <button type="submit"
              class="w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Agregar
      </button>
    </form>
  </div>
  @endif

</div>
