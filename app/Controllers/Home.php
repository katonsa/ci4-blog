<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;

class Home extends BaseController
{
    public function index(): string
    {
        $postModel = new PostModel();

        $posts = $postModel->select('posts.*, users.name as author_name')
            ->join('users', 'users.id = posts.author_id')
            ->where('posts.status', 'published')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();

        return view('home', ['posts' => $posts]);
    }

    public function view(string $slug): string
    {
        $postModel = new PostModel();

        $post = $postModel->select('posts.*, users.name as author_name')
            ->join('users', 'users.id = posts.author_id')
            ->where('posts.slug', $slug)
            ->where('posts.status', 'published')
            ->first();

        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('post/view', ['post' => $post]);
    }
}
