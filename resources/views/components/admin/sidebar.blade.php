@props(['menuItems' => [
    ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'admin.dashboard'],
    ['label' => 'Postingan', 'icon' => 'file-text', 'route' => 'admin.posts.index'],
    ['label' => 'Dokumen', 'icon' => 'file', 'route' => 'admin.documents.index'],
    ['label' => 'Kategori', 'icon' => 'tags', 'route' => 'admin.categories.index'],
    ['label' => 'Layanan', 'icon' => 'briefcase', 'route' => 'admin.services.index'],
    ['label' => 'Galeri', 'icon' => 'image', 'route' => 'admin.galleries.index'],
    ['label' => 'Halaman', 'icon' => 'file-text', 'route' => 'admin.pages.index'],
    ['label' => 'Staf', 'icon' => 'users', 'route' => 'admin.staff.index'],
    ['label' => 'Pesan Masuk', 'icon' => 'mail', 'route' => 'admin.contacts.index'],
    ['label' => 'Pengaturan', 'icon' => 'settings', 'route' => 'admin.settings.index'],
]])

<aside class="hidden lg:flex lg:flex-col w-64 bg-primary-950 dark:bg-[#0a0f1a] text-white">
    <div class="flex items-center gap-3 px-6 py-5 border-b border-primary-800">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white shadow-sm">RB</div>
        <div class="text-sm">
            <p class="font-semibold leading-tight font-heading">Admin Panel</p>
            <p class="text-xs text-primary-300 leading-tight">{{ auth()->user()?->name }}</p>
        </div>
    </div>
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        @foreach ($menuItems as $item)
            @php $active = request()->routeIs($item['route'] . '*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $active ? 'bg-primary-600 text-white shadow-sm' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }}"
               wire:navigate
            >
                <x-admin.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
    <div class="border-t border-primary-800 px-3 py-3 space-y-1">
        <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-primary-200 transition hover:bg-primary-800 hover:text-white">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Website
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-primary-200 transition hover:bg-primary-800 hover:text-white">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
