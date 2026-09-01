export function updateStats() {
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

document.addEventListener('DOMContentLoaded', () => {
    updateStats();
});
