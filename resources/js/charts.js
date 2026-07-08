import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;

document.addEventListener('alpine:init', () => {
    Alpine.data('chartComponent', () => ({
        chart: null,
        initChart(options) {
            this.chart = new ApexCharts(this.$el, {
                ...options,
                chart: {
                    ...options.chart,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                },
            });
            this.chart.render();
        },
        destroy() {
            this.chart?.destroy();
        },
    }));
});
