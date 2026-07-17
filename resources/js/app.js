import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const sidebar = document.querySelector('[data-sidebar]');
const overlay = document.querySelector('[data-overlay]');

function toggleSidebar(force) {
    if (!sidebar || !overlay) return;
    const open = force ?? !sidebar.classList.contains('open');
    sidebar.classList.toggle('open', open);
    overlay.classList.toggle('open', open);
}

document.querySelector('[data-menu-toggle]')?.addEventListener('click', () => toggleSidebar());
overlay?.addEventListener('click', () => toggleSidebar(false));

document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm || 'Are you sure?')) {
            event.preventDefault();
        }
    });
});

