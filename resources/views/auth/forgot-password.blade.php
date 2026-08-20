{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña — MailRouter</title>
  @vite(['resources/css/app.css'])
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
  </style>
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">

  {{-- Mensaje de éxito --}}
  @if (session('status'))
  <div class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
              bg-green-900 border border-green-700 text-green-300 flex items-center gap-2">
    ✓ {{ session('status') }}
  </div>
  @endif

  {{-- Errores --}}
  @if($errors->any())
  <div class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
              bg-red-900 border border-red-700 text-red-300">
    ✕ {{ $errors->first() }}
  </div>
  @endif

  <div class="w-full max-w-sm px-6">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-10">
      <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-2xl mb-4">
        📨
      </div>
      <h1 class="text-2xl font-bold text-white mono">MailRouter</h1>
      <p class="text-sm text-gray-500 mt-1">Recuperar contraseña</p>
    </div>

    {{-- Descripción --}}
    <p class="text-sm text-gray-400 text-center mb-6 leading-relaxed">
      Introduce tu email y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
          Email
        </label>
        <input type="email" name="email" value="{{ old('email') }}"
               required autofocus
               placeholder="tu@email.com"
               class="w-full bg-gray-900 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }}
                      rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600
                      focus:outline-none focus:border-indigo-500 transition-colors">
        @error('email')
        <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit"
              class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm
                     font-semibold rounded-lg transition-colors">
        Enviar enlace de recuperación
      </button>

    </form>

    {{-- Volver al login --}}
    <div class="text-center mt-6">
      <a href="{{ route('login') }}"
         class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
        ← Volver al inicio de sesión
      </a>
    </div>

    <p class="text-center text-xs text-gray-600 mt-8 mono">
      MailRouter — Sistema de gestión de correos
    </p>
  </div>

  <script>
    // Auto-dismiss flash messages
    setTimeout(() => {
      document.querySelectorAll('.fixed').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
      });
    }, 4000);
  </script>

</body>
</html>
