import Alpine from 'alpinejs';
import { createIcons } from 'lucide';
import facebook from 'lucide/dist/esm/icons/facebook';
import instagram from 'lucide/dist/esm/icons/instagram';
import messageCircle from 'lucide/dist/esm/icons/message-circle';

window.Alpine = Alpine;

const initLucide = () => createIcons({ icons: { facebook, instagram, messageCircle } });
initLucide();
document.addEventListener('alpine:after', initLucide);

function lockScroll(bodyStyle) {
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    bodyStyle.paddingRight = `${scrollbarWidth}px`;
    bodyStyle.overflow = 'hidden';
}

function unlockScroll(bodyStyle) {
    bodyStyle.paddingRight = '';
    bodyStyle.overflow = '';
}

document.addEventListener('alpine:init', () => {
    Alpine.data('navigation', () => ({
        isOpen: false,
        openMobile: null,
        openDropdown: null,
        openSearch: false,
        scrolled: false,

        toggle() {
            this.isOpen = !this.isOpen;
            const body = document.body.style;
            if (this.isOpen) {
                lockScroll(body);
            } else {
                unlockScroll(body);
            }
        },

        init() {
            const body = document.body.style;

            this.$watch('isOpen', (val) => {
                if (!val) {
                    this.openMobile = null;
                    unlockScroll(body);
                }
            });

            this.$watch('openSearch', (val) => {
                if (val) {
                    this.$nextTick(() => {
                        this.$refs?.searchInput?.focus();
                    });
                    body.overflow = 'hidden';
                } else {
                    body.overflow = '';
                }
            });
        },

        initScroll() {
            const onScroll = () => {
                this.scrolled = window.scrollY > 20;
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
            this.$watch('$el', () => {
                window.removeEventListener('scroll', onScroll);
            });
        },
    }));

    Alpine.data('lightbox', () => ({
        open: false,
        currentIndex: 0,
        images: [],

        openGallery(index, images) {
            this.images = images;
            this.currentIndex = index;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.open = false;
            document.body.style.overflow = '';
        },

        next() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },

        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        },

        init() {
            const onKeydown = (e) => {
                if (!this.open) return;
                if (e.key === 'Escape') return this.close();
                if (e.key === 'ArrowRight') return this.next();
                if (e.key === 'ArrowLeft') return this.prev();
            };
            document.addEventListener('keydown', onKeydown);
            this.$watch('open', (val) => {
                if (!val) document.removeEventListener('keydown', onKeydown);
            });
        },
    }));

    Alpine.data('formHandler', () => ({
        loading: false,
        success: false,
        errors: {},
        async submit() {
            this.loading = true;
            this.errors = {};
            this.success = false;
            const form = this.$el;
            const data = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: data,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (response.ok) {
                    this.success = true;
                    form.reset();
                } else if (response.status === 422) {
                    const json = await response.json();
                    this.errors = json.errors || {};
                }
            } catch {
                this.errors._general = 'Terjadi kesalahan. Silakan coba lagi.';
            } finally {
                this.loading = false;
            }
        },
    }));

    Alpine.data('scrollReveal', () => ({
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            this.$el.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        },
    }));

    Alpine.data('countUp', () => ({
        target: 0,
        current: 0,
        duration: 2000,
        start(val) {
            this.target = val;
            this.animate();
        },
        animate() {
            const startTime = performance.now();
            const step = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / this.duration, 1);
                this.current = Math.floor(progress * this.target);
                this.$el.textContent = this.current.toLocaleString();
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        },
    }));
});

Alpine.start();
