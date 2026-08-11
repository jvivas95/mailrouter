{{-- resources/views/partials/config.blade.php --}}
<div id="config-section" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-800">
    <h2 class="text-sm font-semibold text-white">⚙ Configuración del servidor</h2>
  </div>
  <div class="p-6">
    <form method="POST" action="/config">
      @csrf
      <div class="grid grid-cols-2 gap-4">

        <div class="col-span-2">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
            Email monitoreado
          </label>
          <input type="email" name="email_address"
                 value="{{ $config['email_address'] ?? '' }}"
                 placeholder="pedidosutpr@gmail.com"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
        </div>

        <div class="col-span-2">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
            Contraseña / App Password
          </label>
          <input type="password" name="email_password"
                 value="{{ $config['email_password'] ?? '' }}"
                 placeholder="App Password de Gmail"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
            Servidor IMAP
          </label>
          <input type="text" name="imap_host"
                 value="{{ $config['imap_host'] ?? 'imap.gmail.com' }}"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                        focus:outline-none focus:border-indigo-500 transition-colors">
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
            Servidor SMTP
          </label>
          <input type="text" name="smtp_host"
                 value="{{ $config['smtp_host'] ?? 'smtp.gmail.com' }}"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                        focus:outline-none focus:border-indigo-500 transition-colors">
        </div>

        <div class="col-span-2">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
            Intervalo de revisión (segundos)
          </label>
          <input type="number" name="check_interval"
                 value="{{ $config['check_interval'] ?? 60 }}"
                 min="10" max="3600"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white
                        focus:outline-none focus:border-indigo-500 transition-colors">
        </div>

      </div>

      <button type="submit"
              class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        💾 Guardar configuración
      </button>
    </form>

    <div class="mt-4 p-4 bg-indigo-900/10 border border-indigo-800/30 rounded-lg text-xs text-gray-400 leading-relaxed">
      <strong class="text-indigo-400">📌 Gmail:</strong>
      Activa IMAP en Ajustes → Reenvío e IMAP.
      Usa una <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-indigo-400 underline">
        App Password
      </a> si tienes verificación en dos pasos.
    </div>
  </div>
</div>
