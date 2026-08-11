{{-- resources/views/partials/users.blade.php --}}
<div id="users-section" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-800">
    <h2 class="text-sm font-semibold text-white">🔑 Gestión de usuarios</h2>
  </div>

  {{-- Lista de usuarios --}}
  <div class="divide-y divide-gray-800">
    @foreach($users as $user)
    <div class="flex items-center gap-4 px-6 py-3">
      <div class="w-8 h-8 rounded-full bg-indigo-700 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
      </div>
      <span class="text-xs font-semibold px-2 py-1 rounded flex-shrink-0
        {{ $user->role === 'admin'
            ? 'bg-indigo-900/40 text-indigo-400'
            : 'bg-gray-800 text-gray-400' }}">
        {{ $user->role }}
      </span>

      @if($user->id !== auth()->id())
      <form method="POST" action="/users/{{ $user->id }}"
            onsubmit="return confirm('¿Eliminar a {{ $user->name }}?')">
        @csrf @method('DELETE')
        <button type="submit"
                class="p-1.5 text-xs bg-red-900/20 border border-red-800/40 text-red-400 rounded hover:bg-red-900/40 transition-colors">
          ✕
        </button>
      </form>
      @else
      <span class="text-xs text-gray-600 px-2">tú</span>
      @endif
    </div>
    @endforeach
  </div>

  {{-- Formulario nuevo usuario --}}
  <div class="px-6 py-4 border-t border-gray-800 bg-gray-950/40">
    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Añadir usuario</p>
    <form method="POST" action="/users">
      @csrf
      <div class="grid grid-cols-2 gap-3 mb-3">
        <input type="text" name="name" placeholder="Nombre" required
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                      placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
        <input type="email" name="email" placeholder="email@empresa.com" required
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                      placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
        <input type="password" name="password" placeholder="Contraseña" required
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                      placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
        <select name="role"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                       focus:outline-none focus:border-indigo-500 transition-colors">
          <option value="user">user — solo lectura</option>
          <option value="admin">admin — acceso total</option>
        </select>
      </div>
      <button type="submit"
              class="w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Añadir usuario
      </button>
    </form>
  </div>
</div>
