<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ContactSubmissionRepositoryInterface;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;

class DashboardController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected DocumentRepositoryInterface $documentRepo,
        protected ContactSubmissionRepositoryInterface $contactRepo,
    ) {}

    public function index()
    {
        $postCount = $this->postRepo->count();
        $documentCount = $this->documentRepo->count();
        $contactCount = $this->contactRepo->unreadCount();
        $recentPosts = $this->postRepo->latest(5);
        $recentContacts = $this->contactRepo->latest(5);

        return view('admin.dashboard', compact(
            'postCount', 'documentCount', 'contactCount',
            'recentPosts', 'recentContacts',
        ));
    }
}
