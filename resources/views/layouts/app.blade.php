<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'MailRouter')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #0d0d14; }
    ::-webkit-scrollbar-thumb { background: #252538; border-radius: 3px; }
  </style>
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen">

  {{-- Flash messages --}}
  @if (session('success'))
    <div class="flash-msg fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
                bg-green-900 border border-green-700 text-green-300 flex items-center gap-2">
      <span>✓</span> {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="flash-msg fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium
                bg-red-900 border border-red-700 text-red-300 flex items-center gap-2">
      <span>✕</span> {{ session('error') }}
    </div>
  @endif

  @yield('content')

  {{-- Script global para flash messages --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.flash-msg').forEach(flash => {
        flash.style.transform   = 'translateX(40px)';
        flash.style.opacity     = '0';
        flash.style.transition  = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';

        requestAnimationFrame(() => requestAnimationFrame(() => {
          flash.style.transform = 'translateX(0)';
          flash.style.opacity   = '1';
        }));

        setTimeout(() => {
          flash.style.transform = 'translateX(40px)';
          flash.style.opacity   = '0';
          setTimeout(() => flash.remove(), 400);
        }, 4000);
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
