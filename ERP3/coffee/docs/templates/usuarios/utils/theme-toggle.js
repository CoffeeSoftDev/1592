// Theme toggle (dark/light) con localStorage
(function () {
    const KEY = 'coffee-theme';
    const html = document.documentElement;

    // Aplicar tema guardado
    const saved = localStorage.getItem(KEY) || 'dark';
    html.setAttribute('data-theme', saved);

    // Listener al boton si existe
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;

        btn.addEventListener('click', function () {
            const current = html.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem(KEY, next);
        });
    });
})();
