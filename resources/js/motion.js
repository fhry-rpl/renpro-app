import Alpine from 'alpinejs';
import { animate, inView, scroll, stagger } from 'motion';

document.addEventListener('alpine:init', () => {
    Alpine.data('countUp', () => ({
        value: 0,
        target: 0,
        init() {
            this.target = parseInt(this.$el.dataset.target || '0');
            const prefersMotion = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!prefersMotion) {
                this.value = this.target;
                return;
            }
            inView(this.$el, () => {
                animate((progress) => {
                    this.value = Math.floor(progress * this.target);
                }, { duration: 1.5, easing: [0.25, 0.1, 0.25, 1] });
            }, { amount: 0.3 });
        },
    }));
});
