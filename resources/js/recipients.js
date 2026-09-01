// resources/js/recipients.js
import Sortable from 'sortablejs';

// Function to initialize the drag-and-drop functionality for the recipients list
export function initRecipients() {

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
}

export function updateNextBadge(items) {

    const list = document.getElementById('recipients-list');

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

document.addEventListener('DOMContentLoaded', () => {
    initRecipients();
});
