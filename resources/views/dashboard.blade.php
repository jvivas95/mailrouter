{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard — MailRouter')

@section('content')
<div class="flex min-h-screen">

{{-- Sidebar --}}
@include('partials.sidebar')

    {{-- Main --}}
    <div class="flex-1 ml-60 flex flex-col">

        {{-- Header --}}
        <x-header/>

        {{-- Stats --}}
        <x-emails-stats
            :stats="$stats"
            :emails="$emails"
        />

        {{-- Grid principal --}}
        <div class="grid grid-cols-3 gap-6 items-start">

            {{-- Columna izquierda (2/3) --}}
            <div class="col-span-2 space-y-6">



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
