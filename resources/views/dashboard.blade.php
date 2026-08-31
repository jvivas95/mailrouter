{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard — MailRouter')

@section('content')
<div class="flex min-h-screen">

  {{-- Sidebar --}}
  @include('partials.sidebar')

  {{-- Main --}}
  <div class="flex-1 ml-60 flex flex-col">

    {{-- Topbar --}}
    <header class="sticky top-0 z-40 bg-gray-900 border-b border-gray-800 px-8 py-4 flex items-center justify-between">
      <h1 class="text-base font-semibold text-white mono">📨 MailRouter</h1>

      @if(auth()->user()->isAdmin())
      <div class="flex items-center gap-2">

        {{-- Revisar ahora --}}
        <form method="POST" action="/worker/check-now">
          @csrf
          <button type="submit"
                  class="px-3 py-1.5 text-sm bg-gray-800 border border-gray-700 text-gray-300
                         rounded-lg hover:bg-gray-700 transition-colors">
            ↻ Revisar ahora
          </button>
        </form>

        {{-- Iniciar / Detener --}}
        @if($config['active'] ?? false)
        <form method="POST" action="/worker/stop">
          @csrf
          <button type="submit"
                  class="px-3 py-1.5 text-sm bg-red-900/30 border border-red-800/50 text-red-400
                         rounded-lg hover:bg-red-900/50 transition-colors">
            ⏹ Detener monitor
          </button>
        </form>
        @else
        <form method="POST" action="/worker/start">
          @csrf
          <button type="submit"
                  class="px-3 py-1.5 text-sm bg-green-900/30 border border-green-800/50 text-green-400
                         rounded-lg hover:bg-green-900/50 transition-colors">
            ▶ Iniciar monitor
          </button>
        </form>
        @endif

      </div>
      @endif
    </header>

    {{-- Contenido --}}
    <main class="flex-1 px-8 py-8">

      {{-- Stats --}}
      <div id="overview" class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total recibidos</p>
          <p id="stat-total" class="text-3xl font-bold text-indigo-400 mono">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Reenviados</p>
          <p id="stat-forwarded" class="text-3xl font-bold text-green-400 mono">{{ $stats['forwarded'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pendientes</p>
          <p id="stat-pending" class="text-3xl font-bold text-yellow-400 mono">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Errores</p>
          <p id="stat-errors" class="text-3xl font-bold text-red-400 mono">{{ $stats['errors'] }}</p>
        </div>
      </div>

      {{-- Grid principal --}}
      <div class="grid grid-cols-3 gap-6 items-start">

        {{-- Columna izquierda (2/3) --}}
        <div class="col-span-2 space-y-6">

          {{-- Tabla de correos --}}
          <div id="emails-section" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
              <h2 class="text-sm font-semibold text-white">✉ Correos recibidos</h2>
              <span class="text-xs text-gray-500 mono">Últimos {{ $emails->count() }}</span>
            </div>

            @if($emails->isNotEmpty())
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr class="border-b border-gray-800">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Remitente</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Asunto</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Reenviado a</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Fecha</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-6">Estado</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($emails as $email)
                  <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">

                    <td class="py-3 px-6 text-sm text-white max-w-[180px] truncate">
                      {{ $email->sender }}
                    </td>

                    <td class="py-3 px-6 text-sm text-gray-400 max-w-[220px] truncate">
                      {{ $email->subject }}
                    </td>

                    <td class="py-3 px-6 text-xs text-gray-500 max-w-[160px] truncate">
                      {{ $email->forwarded_to ?? '—' }}
                    </td>

                    <td class="py-3 px-6 text-xs text-gray-500 mono whitespace-nowrap">
                      {{ $email->created_at?->format('d/m/Y H:i') ?? '—' }}
                    </td>

                    <td class="py-3 px-6">
                      @if($email->status === 'forwarded')
                        <span class="px-2 py-1 rounded text-xs mono bg-green-900/40 text-green-400">✓ enviado</span>
                      @elseif($email->status === 'error')
                        <span class="px-2 py-1 rounded text-xs mono bg-red-900/40 text-red-400">✕ error</span>
                      @elseif($email->status === 'pending')
                        <span class="px-2 py-1 rounded text-xs mono bg-yellow-900/40 text-yellow-400">⏳ pendiente</span>
                      @else
                        <span class="px-2 py-1 rounded text-xs mono bg-gray-800 text-gray-500">— sin dest.</span>
                      @endif
                    </td>

                    <td class="py-3 px-4">
                      <a href="/emails/{{ $email->id }}"
                         class="px-2 py-1 text-xs bg-gray-800 border border-gray-700 text-gray-400
                                rounded hover:bg-gray-700 transition-colors">
                        ver
                      </a>
                    </td>

                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
            <div class="py-16 text-center">
              <p class="text-4xl mb-3 opacity-30">📭</p>
              <p class="text-sm text-gray-500">No hay correos registrados aún</p>
            </div>
            @endif
          </div>

          {{-- Config — solo admin --}}
          @if(auth()->user()->isAdmin())
            @include('partials.config')
            @include('partials.users')
          @endif

        </div>

        {{-- Columna derecha (1/3) --}}
        <div>
          @include('partials.recipients')
        </div>

      </div>{{-- /grid --}}
    </main>
  </div>{{-- /main --}}
</div>{{-- /flex --}}
@endsection

@push('scripts')
{{-- SortableJS para drag and drop de destinatarios --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
  // ── Drag and drop de destinatarios ─────────────────────────────────
  @if(auth()->user()->isAdmin())
  const list = document.getElementById('recipients-list');

  if (list) {
    Sortable.create(list, {
      animation:   150,
      ghostClass:  'opacity-30',
      handle:      '.drag-handle',

      onEnd: function () {
        const items = [...list.querySelectorAll('li[data-id]')];
        const order = items.map(li => li.dataset.id);

        fetch('/recipients/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ order }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Actualizar el badge NEXT visualmente
                // El primero activo de la nueva lista es el NEXT
                window.location.reload();
            }
        })
        .catch(() => showToast('Error al guardar el orden', true));
    }
    });
  }
  @endif

    function updateNextBadge(items) {
        // Quitar todos los badges NEXT existentes
        document.querySelectorAll('.next-badge').forEach(b => b.remove());

        // El servidor ya avanzó el índice, así que necesitamos
        // pedir el nuevo estado via API
        fetch('/api/next-recipient')
            .then(r => r.json())
            .then(data => {
                if (!data.id) return;
                console.log('Next recipient:', data); // ← añade esto
                // Encontrar el li correspondiente y añadir el badge
                const targetLi = list.querySelector(`li[data-id="${data.id}"]`);
                if (!targetLi) return;

                console.log('Target li:', targetLi); // ← y esto

                // Badge creation
                const badge = document.createElement('span');
                badge.className = 'next-badge text-xs font-semibold text-indigo-400 bg-indigo-900/30 border border-indigo-800/50 px-1.5 py-0.5 rounded flex-shrink-0';
                badge.textContent = 'NEXT';

                // Actualizar también el bloque "Siguiente en recibir"
                const nextName  = targetLi.querySelector('.recipient-name')?.textContent;
                const nextEmail = targetLi.querySelector('.recipient-email')?.textContent;

                const nameEl  = document.getElementById('next-recipient-name');
                const emailEl = document.getElementById('next-recipient-email');
                if (nameEl)  nameEl.textContent  = nextName;
                if (emailEl) emailEl.textContent = nextEmail;


                // Insertar al final del li, antes del div de acciones
                // Si no encuentra recipient-actions, lo añade al final del li
                const actionsDiv = targetLi.querySelector('.recipient-actions');
                if (actionsDiv) {
                    actionsDiv.before(badge);
                } else {
                    targetLi.appendChild(badge);
                }
            });
    }
  // ── Polling de stats cada 30 segundos ──────────────────────────────
  function updateStats() {
    fetch('/api/stats')
      .then(r => r.json())
      .then(data => {
        const map = {
          'stat-total':     data.total,
          'stat-forwarded': data.forwarded,
          'stat-pending':   data.pending,
          'stat-errors':    data.errors,
        };

        Object.entries(map).forEach(([id, val]) => {
          const el = document.getElementById(id);
          if (!el || el.textContent === String(val)) return;

          el.style.transition = 'all 0.3s ease';
          el.style.transform  = 'scale(1.15)';
          el.style.color      = '#fff';
          el.textContent      = val;

          setTimeout(() => {
            el.style.transform = 'scale(1)';
            el.style.color     = '';
          }, 300);
        });
      })
      .catch(() => {});
  }

  setInterval(updateStats, 30_000);

  // ── Toast helper ────────────────────────────────────────────────────
  function showToast(msg, isError = false) {
    const toast = document.createElement('div');
    toast.className = [
      'fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-sm font-medium',
      'flex items-center gap-2 transition-all',
      isError
        ? 'bg-red-900 border border-red-700 text-red-300'
        : 'bg-green-900 border border-green-700 text-green-300',
    ].join(' ');
    toast.textContent = (isError ? '✕ ' : '✓ ') + msg;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 400);
    }, 2500);
  }
</script>
@endpush
