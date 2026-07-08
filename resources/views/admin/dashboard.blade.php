@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="card-dashboard p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-dark-muted">Total Postingan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-dark-text font-heading">{{ $postCount }}</p>
                </div>
            </div>
        </div>
        <div class="card-dashboard p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-dark-muted">Total Dokumen</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-dark-text font-heading">{{ $documentCount }}</p>
                </div>
            </div>
        </div>
        <div class="card-dashboard p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-dark-muted">Pesan Belum Dibaca</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-dark-text font-heading">{{ $contactCount }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <div class="card-dashboard p-6">
            <h2 class="heading-h3 text-gray-900 dark:text-dark-text">Postingan Terbaru</h2>
            @if ($recentPosts->isEmpty())
                <p class="mt-4 text-sm text-gray-500 dark:text-dark-muted">Belum ada postingan.</p>
            @else
                <ul class="mt-4 divide-y divide-border dark:divide-dark-border">
                    @foreach ($recentPosts as $post)
                        <li class="py-3 first:pt-0 last:pb-0">
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-sm font-medium text-gray-900 dark:text-dark-text hover:text-primary-600 dark:hover:text-primary-400 transition">{{ $post->title }}</a>
                            <p class="text-xs text-gray-500 dark:text-dark-muted">{{ $post->published_at?->format('d F Y') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="card-dashboard p-6">
            <h2 class="heading-h3 text-gray-900 dark:text-dark-text">Pesan Masuk Terbaru</h2>
            @if ($recentContacts->isEmpty())
                <p class="mt-4 text-sm text-gray-500 dark:text-dark-muted">Belum ada pesan.</p>
            @else
                <ul class="mt-4 divide-y divide-border dark:divide-dark-border">
                    @foreach ($recentContacts as $contact)
                        <li class="py-3 first:pt-0 last:pb-0">
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="text-sm font-medium text-gray-900 dark:text-dark-text hover:text-primary-600 dark:hover:text-primary-400 transition">{{ $contact->subject }}</a>
                            <p class="text-xs text-gray-500 dark:text-dark-muted">{{ $contact->name }} — {{ $contact->created_at->format('d F Y') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
