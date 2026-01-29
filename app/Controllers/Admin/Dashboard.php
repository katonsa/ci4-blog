<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $postModel = new PostModel();

        $data = [
            'title'          => 'Dashboard',
            'active'         => 'dashboard',
            'totalPosts'     => $postModel->countAll(),
            'publishedPosts' => $postModel->countByStatus('published'),
            'draftPosts'     => $postModel->countByStatus('draft'),
            'recentPosts'    => $postModel->getRecentWithAuthor(5),
        ];

        return view('admin/dashboard', $data);
    }
}
