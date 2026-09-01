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

window.showToast = showToast; // Global function to be accessible from other scripts
