{{-- resources/views/emails/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Correo — MailRouter')

@section('content')
<div class="min-h-screen px-8 py-10 max-w-3xl mx-auto">

  <a href="/"
     class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-white transition-colors mb-6">
    ← Volver al dashboard
  </a>

  <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">

    {{-- Cabecera --}}
    <div class="px-8 py-6 border-b border-gray-800 bg-gray-900">
      <h1 class="text-lg font-bold text-white mono mb-4">
        {{ $email->subject ?? '(sin asunto)' }}
      </h1>
      <div class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1.5 text-sm">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">De</span>
        <span class="text-gray-300">{{ $email->sender }}</span>

        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">Fecha</span>
        <span class="text-gray-300 mono text-xs">{{ $email->created_at?->format('d/m/Y H:i:s') }}</span>

        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">Estado</span>
        <span>
          @if($email->status === 'forwarded')
            <span class="px-2 py-1 rounded text-xs mono bg-green-900/40 text-green-400">✓ reenviado</span>
          @elseif($email->status === 'error')
            <span class="px-2 py-1 rounded text-xs mono bg-red-900/40 text-red-400">✕ error</span>
          @elseif($email->status === 'pending')
            <span class="px-2 py-1 rounded text-xs mono bg-yellow-900/40 text-yellow-400">⏳ pendiente</span>
          @else
            <span class="px-2 py-1 rounded text-xs mono bg-gray-800 text-gray-500">— sin destinatarios</span>
          @endif
        </span>

        @if($email->forwarded_to)
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">Enviado a</span>
        <span class="text-gray-300">{{ $email->forwarded_to }}</span>
        @endif

        @if($email->forwarded_at)
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">Enviado el</span>
        <span class="text-gray-300 mono text-xs">{{ \Carbon\Carbon::parse($email->forwarded_at)->format('d/m/Y H:i:s') }}</span>
        @endif

        @if($email->attachments_count > 0)
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">Adjuntos</span>
        <span class="text-gray-300">{{ $email->attachments_count }} archivo(s)</span>
        @endif

        @if($email->requeue_count > 0)
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-0.5">Reencolado</span>
        <span class="text-gray-300">{{ $email->requeue_count }} vez/veces</span>
        @endif
      </div>
    </div>

    {{-- Badge reenviado --}}
    @if($email->forwarded_to)
    <div class="mx-6 mt-5 p-3 bg-green-900/10 border border-green-800/30 rounded-lg text-xs text-green-400">
      ✓ Reenviado a <strong>{{ $email->forwarded_to }}</strong>
    </div>
    @endif

    {{-- Cuerpo --}}
    <div class="px-8 py-6 text-sm text-gray-300 leading-relaxed whitespace-pre-wrap">
      {{ $email->body ?? '(sin contenido)' }}
    </div>

  </div>
</div>
@endsection
