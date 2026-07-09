@extends('layouts.admin')

@section('title', isset($gallery) ? 'Edit Galeri' : 'Tambah Galeri')

@section('content')
    <form method="POST" action="{{ isset($gallery) ? route('admin.galleries.update', $gallery->id) : route('admin.galleries.store') }}" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        @if (isset($gallery)) @method('PUT') @endif
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Judul <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $gallery->title ?? '') }}" required maxlength="255" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3" maxlength="1000" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $gallery->description ?? '') }}</textarea>
            </div>
            <div x-data="{ coverSize: 0 }">
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp" @change="coverSize = $event.target.files[0]?.size || 0" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-600 hover:file:bg-indigo-100">
                <p x-show="coverSize > 2097152" class="mt-1 text-xs text-red-600">Ukuran cover tidak boleh lebih dari 2 MB.</p>
            </div>
            <div x-data="{ imagesTooLarge: false }">
                <label for="images" class="block text-sm font-medium text-gray-700">Foto {{ isset($gallery) ? '(biarkan kosong jika tidak diubah)' : '' }} <span class="text-red-500">*</span></label>
                <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp" @change="imagesTooLarge = [...$event.target.files].some(f => f.size > 2097152)" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-600 hover:file:bg-indigo-100" {{ isset($gallery) ? '' : 'required' }}>
                <p x-show="imagesTooLarge" class="mt-1 text-xs text-red-600">Setiap foto tidak boleh lebih dari 2 MB.</p>
                @error('images') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $gallery->is_published ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Terbitkan</span>
                </label>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">{{ isset($gallery) ? 'Perbarui' : 'Simpan' }}</button>
                <a href="{{ route('admin.galleries.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">Batal</a>
            </div>
        </div>
    </form>
@endsection
