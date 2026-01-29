<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display all users with their roles
     */
    public function index()
    {
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        // Get roles for each user
        foreach ($users as &$user) {
            $user['roles'] = $this->userModel->getRoles($user['id']);
        }

        $data = [
            'title'  => 'Users',
            'active' => 'users',
            'users'  => $users,
        ];

        return view('admin/users/index', $data);
    }
}
