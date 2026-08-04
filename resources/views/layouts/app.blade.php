<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'MailRouter')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen">

  @if (session('success'))
  <div class="flash-msg fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm
              bg-green-900 border border-green-700 text-green-300">
    ✓ {{ session('success') }}
  </div>
  @endif

  @if (session('error'))
  <div class="flash-msg fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm
              bg-red-900 border border-red-700 text-red-300">
    ✕ {{ session('error') }}
  </div>
  @endif

  @yield('content')

</body>
</html>
