<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        // TODO: Get real stats from PostModel after Phase 2
        $data = [
            'title'          => 'Dashboard',
            'active'         => 'dashboard',
            'totalPosts'     => 0,
            'publishedPosts' => 0,
            'draftPosts'     => 0,
            'recentPosts'    => [],
        ];

        return view('admin/dashboard', $data);
    }
}
