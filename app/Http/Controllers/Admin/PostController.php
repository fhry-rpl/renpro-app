<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected CategoryRepositoryInterface $categoryRepo,
    ) {}

    public function index()
    {
        $posts = $this->postRepo->all(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function create()
    {
        $categories = $this->categoryRepo->all();
        return view('admin.posts.form', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        Post::create($request->validated() + ['user_id' => auth()->id()]);
        return redirect()->route('admin.posts.index')
            ->with('success', 'Postingan berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        $categories = $this->categoryRepo->all();
        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return redirect()->route('admin.posts.index')
            ->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')
            ->with('success', 'Postingan berhasil dihapus.');
    }
}
