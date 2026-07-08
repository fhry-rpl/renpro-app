declare global {
    interface Window {
        Alpine: typeof import('alpinejs')['default'];
        ApexCharts: typeof import('apexcharts');
    }
}

export {};
