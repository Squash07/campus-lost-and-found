{{-- Shared <head> assets for the app and guest layouts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind CSS (CDN build, so the free host needs no npm build step) -->
<script src="https://cdn.tailwindcss.com/3.4.17?plugins=forms"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                },
            },
        },
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    :focus-visible { outline: 2px solid #0284c7; outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: 0.01ms !important; animation-duration: 0.01ms !important; }
    }
</style>

<script>
    /*
     * Global UI behaviour (no build step needed):
     *  - forms with data-confirm open the styled confirm modal instead of window.confirm()
     *  - POST forms disable their submit buttons after the first click (no double submits)
     */
    document.addEventListener('alpine:init', () => {
        Alpine.store('confirm', {
            open: false,
            title: '',
            body: '',
            action: 'Confirm',
            tone: 'danger',
            form: null,
            ask(form) {
                this.form = form;
                this.title = form.dataset.confirm || 'Are you sure?';
                this.body = form.dataset.confirmBody || '';
                this.action = form.dataset.confirmAction || 'Confirm';
                this.tone = form.dataset.confirmTone || 'danger';
                this.open = true;
            },
            accept() {
                const form = this.form;
                this.open = false;
                this.form = null;
                if (form) { lockForm(form); form.submit(); }
            },
            cancel() {
                this.open = false;
                this.form = null;
            },
        });
    });

    function lockForm(form) {
        form.querySelectorAll('button[type="submit"], button:not([type])').forEach((btn) => {
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-wait');
            if (btn.dataset.loading) { btn.textContent = btn.dataset.loading; }
        });
    }

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (form.dataset.confirm) {
            event.preventDefault();
            Alpine.store('confirm').ask(form);
            return;
        }
        if ((form.method || 'get').toLowerCase() === 'post') {
            // Let the browser start the request first, then lock the buttons.
            setTimeout(() => lockForm(form), 0);
        }
    });

    // Restore buttons when the page is shown again from the back/forward cache.
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) { window.location.reload(); }
    });
</script>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
