{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="w-60 fixed top-0 left-0 h-screen bg-gray-900 border-r border-gray-800 flex flex-col z-50">

  {{-- Logo --}}
  <div class="px-6 py-6 border-b border-gray-800">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-base">📨</div>
      <span class="font-bold text-white tracking-tight mono">MailRouter</span>
    </div>
  </div>

  {{-- Nav --}}
  <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
    <p class="text-xs font-semibold text-gray-600 uppercase tracking-widest px-3 mb-2">Navegación</p>

    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-indigo-600/10 hover:text-white transition-colors">
      <span>⊞</span> Dashboard
    </a>
    <a href="{{ route('emails.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-indigo-600/10 hover:text-white transition-colors">
      <span>✉</span> Correos
    </a>
    <a href="{{ route('recipients.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-indigo-600/10 hover:text-white transition-colors">
      <span>👥</span> Destinatarios
    </a>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('config.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-indigo-600/10 hover:text-white transition-colors">
      <span>⚙</span> Configuración
    </a>
    <a href="#users-section"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-indigo-600/10 hover:text-white transition-colors">
      <span>🔑</span> Usuarios
    </a>
    @endif
  </nav>

  {{-- Usuario + estado worker --}}
  <div class="px-4 py-4 border-t border-gray-800 space-y-3">

    {{-- Info usuario --}}
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-full bg-indigo-700 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs {{ auth()->user()->isAdmin() ? 'text-indigo-400' : 'text-gray-500' }}">
          {{ auth()->user()->role }}
        </p>
      </div>
      {{-- Logout --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="p-1.5 text-gray-500 hover:text-red-400 transition-colors"
                title="Cerrar sesión">⏻</button>
      </form>
    </div>

    {{-- Estado del worker --}}
    @php $active = ($config['active'] ?? false); @endphp
    @if($active)
    <div class="flex items-center gap-2 px-3 py-2 bg-green-900/20 border border-green-800/40 rounded-lg">
      <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
      </span>
      <span class="text-xs text-green-400 font-medium">Monitor activo</span>
    </div>
    @else
    <div class="flex items-center gap-2 px-3 py-2 bg-red-900/20 border border-red-800/40 rounded-lg">
      <span class="h-2 w-2 rounded-full bg-red-500 opacity-50"></span>
      <span class="text-xs text-red-400 font-medium">Monitor detenido</span>
    </div>
    @endif

  </div>
</aside>
