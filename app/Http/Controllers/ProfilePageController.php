<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\PageRepositoryInterface;
use App\Repositories\Contracts\StaffRepositoryInterface;

class ProfilePageController extends Controller
{
    public function __construct(
        protected PageRepositoryInterface $pageRepo,
        protected StaffRepositoryInterface $staffRepo,
    ) {}

    public function show(string $slug)
    {
        $allowed = ['sejarah', 'visi-misi', 'tugas-fungsi'];

        if ($slug === 'struktur-organisasi') {
            $staff = $this->staffRepo->allActive();
            return view('pages.staff', compact('staff'));
        }

        if (!in_array($slug, $allowed)) {
            abort(404);
        }

        $page = $this->pageRepo->findPublishedBySlug($slug);
        if (!$page) {
            abort(404);
        }
        return view('pages.show', compact('page'));
    }
}
