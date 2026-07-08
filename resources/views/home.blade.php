@extends('layouts.front')

@section('title', config('app.name'))

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-white/10 via-transparent to-transparent"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8 lg:py-36">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm px-4 py-1.5 text-sm text-white/90 border border-white/20 mb-6">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bandar Udara Budiarto — Curug, Tangerang
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl font-heading">
                    Rencana Program & Pengembangan<br>
                    <span class="text-primary-200">UPBU Budiarto</span>
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-white/80 max-w-2xl">
                    Mewujudkan Bandar Udara Budiarto sebagai bandara yang modern, aman, nyaman, dan berkelanjutan melalui perencanaan program yang terintegrasi dan inovatif.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('profile.page', 'visi-misi') }}" class="btn-primary btn-lg shadow-lg shadow-primary-600/25">
                        Visi & Misi
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('services.index') }}" class="inline-flex items-center rounded-xl border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-surface dark:from-dark-bg"></div>
    </section>

    {{-- Stats --}}
    <section class="relative -mt-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="card p-6 text-center shadow-lg">
                    <p class="text-3xl font-bold text-primary-600 font-heading">10+</p>
                    <p class="text-sm text-gray-600 dark:text-dark-muted mt-1">Tahun Pelayanan</p>
                </div>
                <div class="card p-6 text-center shadow-lg">
                    <p class="text-3xl font-bold text-primary-600 font-heading">50+</p>
                    <p class="text-sm text-gray-600 dark:text-dark-muted mt-1">Program Terselesaikan</p>
                </div>
                <div class="card p-6 text-center shadow-lg">
                    <p class="text-3xl font-bold text-accent-600 font-heading">100%</p>
                    <p class="text-sm text-gray-600 dark:text-dark-muted mt-1">Komitmen Pelayanan</p>
                </div>
                <div class="card p-6 text-center shadow-lg">
                    <p class="text-3xl font-bold text-primary-600 font-heading">24/7</p>
                    <p class="text-sm text-gray-600 dark:text-dark-muted mt-1">Dukungan Operasional</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Sektor Layanan --}}
    @if ($services->isNotEmpty())
        <section class="py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="inline-flex items-center rounded-full bg-primary-50 dark:bg-primary-900/30 px-3 py-1 text-sm font-medium text-primary-700 dark:text-primary-300">Layanan</span>
                    <h2 class="mt-4 text-3xl font-bold text-gray-900 dark:text-dark-text font-heading sm:text-4xl">Layanan Kami</h2>
                    <p class="mt-3 text-lg text-gray-600 dark:text-dark-muted">Berbagai layanan yang tersedia di Bandar Udara Budiarto</p>
                </div>
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="group card card-hover p-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300 group-hover:bg-primary-600 group-hover:text-white transition-all duration-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-dark-text font-heading group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">{{ $service->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-dark-muted line-clamp-2">{{ $service->description }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Berita Terbaru --}}
    @if ($latestPosts->isNotEmpty())
        <section class="bg-surface-alt dark:bg-dark-surface-alt py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div class="max-w-xl">
                        <span class="inline-flex items-center rounded-full bg-primary-50 dark:bg-primary-900/30 px-3 py-1 text-sm font-medium text-primary-700 dark:text-primary-300">Berita</span>
                        <h2 class="mt-4 text-3xl font-bold text-gray-900 dark:text-dark-text font-heading sm:text-4xl">Berita Terbaru</h2>
                        <p class="mt-3 text-lg text-gray-600 dark:text-dark-muted">Informasi terkini seputar Bandar Udara Budiarto</p>
                    </div>
                    <a href="{{ route('posts.index') }}" class="btn-outline btn-md shrink-0">
                        Lihat Semua
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestPosts as $post)
                        <article class="group card overflow-hidden">
                            @if ($post->thumbnail)
                                <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover" loading="lazy">
                            @else
                                <div class="h-48 w-full bg-gradient-to-br from-primary-100 dark:from-primary-900/50 to-primary-50 dark:to-primary-950/30 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-primary-300 dark:text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            @endif
                            <div class="p-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-2 py-0.5 rounded-full">{{ $post->category?->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $post->published_at->format('d F Y') }}</span>
                                </div>
                                <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-dark-text font-heading group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-dark-muted line-clamp-2">{{ $post->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-8 text-center sm:hidden">
                    <a href="{{ route('posts.index') }}" class="btn-outline btn-md">
                        Lihat Semua Berita
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif
@endsection
