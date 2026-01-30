<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;

class Posts extends BaseController
{
    protected PostModel $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    /**
     * Display all posts
     */
    public function index()
    {
        $posts = $this->postModel->select('posts.*, users.name as author_name')
            ->join('users', 'users.id = posts.author_id')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'  => 'Posts',
            'active' => 'posts',
            'posts'  => $posts,
        ];

        return view('admin/posts/index', $data);
    }

    /**
     * Show create post form
     */
    public function create()
    {
        $data = [
            'title'      => 'Create Post',
            'active'     => 'posts',
            'validation' => session()->getFlashdata('validation'),
        ];

        return view('admin/posts/form', $data);
    }

    /**
     * Store new post
     */
    public function store()
    {
        if (!$this->validate($this->postModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'title'     => $this->request->getPost('title'),
            'content'   => $this->request->getPost('content'),
            'status'    => $this->request->getPost('status') ?? 'draft',
            'author_id' => session()->get('user_id'),
        ];

        if ($this->postModel->insert($data)) {
            return redirect()->to('/admin/posts')->with('success', 'Post created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create post.');
    }

    /**
     * Show edit post form
     */
    public function edit(int $id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/posts')->with('error', 'Post not found.');
        }

        $data = [
            'title'      => 'Edit Post',
            'active'     => 'posts',
            'post'       => $post,
            'validation' => session()->getFlashdata('validation'),
        ];

        return view('admin/posts/form', $data);
    }

    /**
     * Update post
     */
    public function update(int $id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/posts')->with('error', 'Post not found.');
        }

        if (!$this->validate($this->postModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'title'   => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'status'  => $this->request->getPost('status') ?? 'draft',
        ];

        if ($this->postModel->update($id, $data)) {
            return redirect()->to('/admin/posts')->with('success', 'Post updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update post.');
    }

    /**
     * Delete post
     */
    public function delete(int $id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/posts')->with('error', 'Post not found.');
        }

        if ($this->postModel->delete($id)) {
            return redirect()->to('/admin/posts')->with('success', 'Post deleted successfully.');
        }

        return redirect()->to('/admin/posts')->with('error', 'Failed to delete post.');
    }
}
