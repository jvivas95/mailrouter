<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')
@section('title', 'Dashboard — MailRouter')

@section('content')
<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-60 fixed top-0 left-0 h-screen bg-gray-900 border-r border-gray-800 flex flex-col">
    <div class="px-6 py-6 border-b border-gray-800">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">📨</div>
        <span class="font-bold text-white">MailRouter</span>
      </div>
    </div>

    <div class="flex-1 px-3 py-4">
      <a href="#overview" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400
                                 hover:bg-indigo-600/10 hover:text-white transition-colors">
        ⊞ Dashboard
      </a>
      <!-- más links... -->
    </div>

    <!-- Usuario + logout -->
    <div class="px-4 py-4 border-t border-gray-800">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-8 h-8 rounded-full bg-indigo-700 flex items-center justify-center text-xs font-bold">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
          <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
          <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="ml-auto">
          @csrf
          <button class="text-gray-500 hover:text-red-400 transition-colors">⏻</button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 ml-60">

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 p-8">
      <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total recibidos</p>
        <p class="text-3xl font-bold text-indigo-400 font-mono">{{ $stats['total'] }}</p>
      </div>
      <!-- más tarjetas... -->
    </div>

    <!-- Tabla de correos -->
    <div class="mx-8 bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-800">
            <th class="text-left text-xs text-gray-500 uppercase py-3 px-6">Remitente</th>
            <th class="text-left text-xs text-gray-500 uppercase py-3 px-6">Asunto</th>
            <th class="text-left text-xs text-gray-500 uppercase py-3 px-6">Estado</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($emails as $email)
          <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
            <td class="py-3 px-6 text-sm text-white">{{ $email->sender }}</td>
            <td class="py-3 px-6 text-sm text-gray-400">{{ $email->subject }}</td>
            <td class="py-3 px-6">
              @if ($email->status === 'forwarded')
                <span class="px-2 py-1 rounded text-xs bg-green-900/40 text-green-400">✓ enviado</span>
              @elseif ($email->status === 'error')
                <span class="px-2 py-1 rounded text-xs bg-red-900/40 text-red-400">✕ error</span>
              @else
                <span class="px-2 py-1 rounded text-xs bg-yellow-900/40 text-yellow-400">⏳ pendiente</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="py-16 text-center text-gray-500">No hay correos aún</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
