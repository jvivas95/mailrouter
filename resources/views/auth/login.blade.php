{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — MailRouter</title>
  @vite(['resources/css/app.css'])
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
  </style>
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">

  {{-- Flash errors --}}
  @if($errors->any())
  <div class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
              bg-red-900 border border-red-700 text-red-300">
    ✕ {{ $errors->first() }}
  </div>
  @endif

  @if(session('error'))
  <div class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
              bg-red-900 border border-red-700 text-red-300">
    ✕ {{ session('error') }}
  </div>
  @endif

  <div class="w-full max-w-sm px-6">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-10">
      <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-2xl mb-4">
        📨
      </div>
      <h1 class="text-2xl font-bold text-white mono">MailRouter</h1>
      <p class="text-sm text-gray-500 mt-1">Inicia sesión para continuar</p>
    </div>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
          Email
        </label>
        <input type="email" name="email" value="{{ old('email') }}"
               required autofocus
               placeholder="admin@mailrouter.local"
               class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white
                      placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors">
      </div>

      <div>
        <div class="relative">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                Contraseña
            </label>
            <div class="relative">
                <input id="password-input" type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white
                            placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition-colors pr-20">
                <button type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 hover:text-gray-300 transition-colors">
                <span id="toggle-label">👁️ Mostrar</span>
                </button>
            </div>
        </div>

        @if (Route::has('password.request'))
        <div class="text-right mt-2">
            <a href="{{ route('password.request') }}"
            class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
            ¿Olvidaste la contraseña?
            </a>
        </div>
        @endif
      </div>

      <button type="submit"
              class="w-full mt-2 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm
                     font-semibold rounded-lg transition-colors">
        Iniciar sesión
      </button>
    </form>

    <p class="text-center text-xs text-gray-600 mt-8 mono">
      MailRouter — Sistema de gestión de correos
    </p>
  </div>

</body>
</html>
<script>
function togglePassword() {
  const input = document.getElementById('password-input');
  const label = document.getElementById('toggle-label');
  if (input.type === 'password') {
    input.type = 'text';
    label.textContent = '🙈 Ocultar';
  } else {
    input.type = 'password';
    label.textContent = '👁️ Mostrar';
  }
}
</script>
