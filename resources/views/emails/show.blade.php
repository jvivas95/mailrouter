{{-- resources/views/emails/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Correo — MailRouter')

@section('content')
<div class="mx-auto min-h-screen w-full max-w-3xl px-3 py-6 sm:px-8 sm:py-10">

  <a href="/"
     class="mb-6 inline-flex items-center gap-2 text-sm text-gray-500 transition-colors hover:text-white">
    ← Volver al dashboard
  </a>

  <div class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900">

    {{-- Cabecera --}}
    <div class="border-b border-gray-800 bg-gray-900 px-4 py-5 sm:px-8 sm:py-6">
      <h1 class="mb-4 break-words text-lg font-bold text-white mono">
        {{ $email->subject ?? '(sin asunto)' }}
      </h1>
      <div class="grid grid-cols-1 gap-x-4 gap-y-2 text-sm sm:grid-cols-[auto_1fr]">
        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">De</span>
        <span class="min-w-0 break-all text-gray-300">{{ $email->sender }}</span>

        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Fecha</span>
        <span class="min-w-0 break-all text-xs text-gray-300 mono">{{ $email->created_at?->format('d/m/Y H:i:s') }}</span>

        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</span>
        <span class="min-w-0 break-all">
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
        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Enviado a</span>
        <span class="min-w-0 break-all text-gray-300">{{ $email->forwarded_to }}</span>
        @endif

        @if($email->forwarded_at)
        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Enviado el</span>
        <span class="min-w-0 break-all text-xs text-gray-300 mono">{{ \Carbon\Carbon::parse($email->forwarded_at)->format('d/m/Y H:i:s') }}</span>
        @endif

        @if($email->attachments_count > 0)
        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Adjuntos</span>
        <span class="min-w-0 break-all text-gray-300">{{ $email->attachments_count }} archivo(s)</span>
        @endif

        @if($email->requeue_count > 0)
        <span class="pt-0.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Reencolado</span>
        <span class="min-w-0 break-all text-gray-300">{{ $email->requeue_count }} vez/veces</span>
        @endif
      </div>
    </div>

    {{-- Badge reenviado --}}
    @if($email->forwarded_to)
    <div class="mx-4 mt-5 rounded-lg border border-green-800/30 bg-green-900/10 p-3 text-xs text-green-400 sm:mx-6">
      ✓ Reenviado a <strong class="break-all">{{ $email->forwarded_to }}</strong>
    </div>
    @endif

    {{-- Cuerpo --}}
    <div class="overflow-x-hidden px-4 py-5 text-sm leading-relaxed text-gray-300 whitespace-pre-wrap break-words sm:px-8 sm:py-6">
      {{ $email->body ?? '(sin contenido)' }}
    </div>

  </div>
</div>
@endsection
