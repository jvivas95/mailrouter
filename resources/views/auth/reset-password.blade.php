{{-- resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva contraseña — MailRouter</title>
  @vite(['resources/css/app.css'])
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
  </style>
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">

  {{-- Errores --}}
  @if($errors->any())
  <div class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
              bg-red-900 border border-red-700 text-red-300 max-w-xs">
    <ul class="space-y-1">
      @foreach($errors->all() as $error)
      <li class="flex items-center gap-2"><span>✕</span> {{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="w-full max-w-sm px-6">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-10">
      <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-2xl mb-4">
        📨
      </div>
      <h1 class="text-2xl font-bold text-white mono">MailRouter</h1>
      <p class="text-sm text-gray-500 mt-1">Establecer nueva contraseña</p>
    </div>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
      @csrf

      {{-- Token oculto --}}
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      {{-- Email --}}
      <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
          Email
        </label>
        <input type="email" name="email"
               value="{{ old('email', $request->email) }}"
               required autofocus autocomplete="username"
               placeholder="tu@email.com"
               class="w-full bg-gray-900 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }}
                      rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600
                      focus:outline-none focus:border-indigo-500 transition-colors">
        @error('email')
        <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
        @enderror
      </div>

      {{-- Nueva contraseña --}}
      <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
          Nueva contraseña
        </label>
        <input type="password" name="password"
               required autocomplete="new-password"
               placeholder="Mínimo 8 caracteres"
               class="w-full bg-gray-900 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-700' }}
                      rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600
                      focus:outline-none focus:border-indigo-500 transition-colors">
        @error('password')
        <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
        @enderror
      </div>

      {{-- Confirmar contraseña --}}
      <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
          Confirmar contraseña
        </label>
        <input type="password" name="password_confirmation"
               required autocomplete="new-password"
               placeholder="Repite la contraseña"
               class="w-full bg-gray-900 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-700' }}
                      rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600
                      focus:outline-none focus:border-indigo-500 transition-colors">
        @error('password_confirmation')
        <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit"
              class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm
                     font-semibold rounded-lg transition-colors">
        Restablecer contraseña
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
