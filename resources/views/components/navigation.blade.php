@props(['menuItems' => [
    ['label' => 'Beranda', 'route' => 'home'],
    ['label' => 'Profil', 'children' => [
        ['label' => 'Sejarah', 'route' => 'profile.page', 'params' => ['page' => 'sejarah']],
        ['label' => 'Visi & Misi', 'route' => 'profile.page', 'params' => ['page' => 'visi-misi']],
        ['label' => 'Tugas & Fungsi', 'route' => 'profile.page', 'params' => ['page' => 'tugas-fungsi']],
        ['label' => 'Struktur Organisasi', 'route' => 'profile.page', 'params' => ['page' => 'struktur-organisasi']],
    ]],
    ['label' => 'Berita', 'route' => 'posts.index'],
    ['label' => 'Pengumuman', 'route' => 'pengumuman.index'],
    ['label' => 'Dokumen', 'route' => 'documents.index'],
    ['label' => 'Layanan', 'route' => 'services.index'],
    ['label' => 'Galeri', 'route' => 'galleries.index'],
    ['label' => 'Kontak', 'route' => 'contact.index'],
]])

@php
if (!function_exists('isActive')) {
    function isActive($route, $params = []) {
        if (!request()->route()) return false;
        if ($route === 'home') {
            return request()->route()->named('home');
        }
        return request()->route()->named($route);
    }
    function hasActiveChild($children) {
        foreach ($children as $child) {
            if (isActive($child['route'], $child['params'] ?? [])) return true;
        }
        return false;
    }
}
@endphp

<div
    x-data="navigation()"
    @keydown.escape.window="isOpen = false"
    class="sticky top-0 z-50 w-full border-b border-border dark:border-dark-border bg-white/95 dark:bg-dark-surface/95 backdrop-blur-md"
>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="Beranda">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white shadow-sm transition group-hover:shadow-md group-hover:bg-primary-700">RB</div>
            <div class="hidden sm:block">
                <p class="text-sm font-semibold text-gray-900 dark:text-dark-text font-heading">RENPRO UPBU Budiarto</p>
                <p class="text-xs text-gray-500 dark:text-dark-muted">Bandar Udara Budiarto</p>
            </div>
        </a>
        <nav class="hidden lg:flex lg:items-center lg:gap-1" aria-label="Navigasi utama">
            @foreach ($menuItems as $item)
                <div class="relative">
                    @if (!isset($item['children']))
                        <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                           class="relative rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300
                                  {{ isActive($item['route'], $item['params'] ?? []) ? 'text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-600 dark:text-dark-muted' }}"
                        >{{ $item['label'] }}</a>
                    @else
                        <div class="relative" @mouseenter="clearTimeout(hoverTimeout); openDropdown = '{{ $item['label'] }}'" @mouseleave="hoverTimeout = setTimeout(() => { if (openDropdown === '{{ $item['label'] }}') openDropdown = null }, 200)" @click.outside="openDropdown = null">
                            <button @click="openDropdown = openDropdown === '{{ $item['label'] }}' ? null : '{{ $item['label'] }}'" class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300
                                   {{ hasActiveChild($item['children']) ? 'text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-600 dark:text-dark-muted' }}">
                                {{ $item['label'] }}
                                <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': openDropdown === '{{ $item['label'] }}' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openDropdown === '{{ $item['label'] }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute left-0 top-full mt-1 z-50 w-56 rounded-xl border border-border dark:border-dark-border bg-white dark:bg-dark-surface py-2 shadow-dropdown" role="menu">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                       class="block px-4 py-2.5 text-sm transition hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300
                                              {{ isActive($child['route'], $child['params'] ?? []) ? 'text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-700 dark:text-dark-muted' }}"
                                       role="menuitem"
                                    >{{ $child['label'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
        <div class="flex items-center gap-2">
            <a href="{{ route('search') }}" class="rounded-lg p-2 text-gray-400 dark:text-dark-muted transition hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-300" aria-label="Pencarian">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </a>
            <button @click="toggle()" class="rounded-lg p-2 text-gray-400 dark:text-dark-muted lg:hidden hover:bg-primary-50 dark:hover:bg-primary-900/30" aria-label="Toggle menu" :aria-expanded="isOpen">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': isOpen, 'block': !isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'block': isOpen, 'hidden': !isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Overlay --}}
    <div x-show="isOpen" @click="isOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    {{-- Drawer --}}
    <div x-show="isOpen" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 z-50 w-72 max-w-[85vw] bg-white dark:bg-dark-surface shadow-2xl lg:hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between px-4 py-4 border-b border-border dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">RB</div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-dark-text font-heading">Menu</p>
                </div>
            </div>
            <button @click="isOpen = false" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-surface-alt" aria-label="Tutup menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-3 py-4 space-y-1">
            @foreach ($menuItems as $item)
                <div>
                    @if (!isset($item['children']))
                        <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                           class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300
                                  {{ isActive($item['route'], $item['params'] ?? []) ? 'text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-600 dark:text-dark-muted' }}"
                        >{{ $item['label'] }}</a>
                    @else
                        <div>
                            <button @click="openMobile = openMobile === '{{ $item['label'] }}' ? null : '{{ $item['label'] }}'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-dark-muted hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300">
                                <span>{{ $item['label'] }}</span>
                                <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': openMobile === '{{ $item['label'] }}' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openMobile === '{{ $item['label'] }}'" x-collapse class="ml-4 space-y-1 pb-1">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                       class="flex items-center rounded-lg px-3 py-2 text-sm transition hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300
                                              {{ isActive($child['route'], $child['params'] ?? []) ? 'text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-600 dark:text-dark-muted' }}"
                                    >{{ $child['label'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="border-t border-border dark:border-dark-border px-3 py-4">
            <a href="{{ route('search') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-dark-muted hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Pencarian
            </a>
        </div>
    </div>
</div>
