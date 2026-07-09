import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;

const initLucide = () => createIcons({ icons });
initLucide();
document.addEventListener('alpine:after', initLucide);

document.addEventListener('alpine:init', () => {
    Alpine.data('navigation', () => ({
        isOpen: false,
        openMobile: null,
        openDropdown: null,
        openSearch: false,
        hoverTimeout: null,
        scrolled: false,

        toggle() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.paddingRight = `${scrollbarWidth}px`;
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.paddingRight = '';
                document.body.style.overflow = '';
            }
        },

        init() {
            this.$watch('isOpen', (val) => {
                if (!val) {
                    this.openMobile = null;
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';
                }
            });

            this.$watch('openSearch', (val) => {
                if (val) {
                    this.$nextTick(() => {
                        const input = this.$refs?.searchInput;
                        if (input) setTimeout(() => input.focus(), 100);
                    });
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
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
            document.addEventListener('keydown', (e) => {
                if (!this.open) return;
                if (e.key === 'Escape') this.close();
                if (e.key === 'ArrowRight') this.next();
                if (e.key === 'ArrowLeft') this.prev();
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
