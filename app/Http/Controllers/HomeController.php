<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class HomeController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected ServiceRepositoryInterface $serviceRepo,
    ) {}

    public function index()
    {
        $services = $this->serviceRepo->allActive();
        $latestPosts = $this->postRepo->latest(6);

        return view('home', compact('services', 'latestPosts'));
    }
}
