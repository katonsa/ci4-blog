<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;
    protected RoleModel $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
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

    /**
     * Show create user form
     */
    public function create()
    {
        $roles = $this->roleModel->findAll();

        $data = [
            'title'  => 'Create User',
            'active' => 'users',
            'roles'  => $roles,
        ];

        return view('admin/users/form', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => 1,
        ];

        $userId = $this->userModel->insert($data);

        if ($userId) {
            // Assign roles
            $roles = $this->request->getPost('roles') ?? [];
            $this->userModel->syncRoles($userId, $roles);

            return redirect()->to('/admin/users')->with('success', 'User created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user.');
    }

    /**
     * Show edit user form
     */
    public function edit(int $id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $roles = $this->roleModel->findAll();
        $userRoles = array_column($this->userModel->getRoles($id), 'id');

        $data = [
            'title'      => 'Edit User',
            'active'     => 'users',
            'user'       => $user,
            'roles'      => $roles,
            'user_roles' => $userRoles,
        ];

        return view('admin/users/form', $data);
    }

    /**
     * Update user
     */
    public function update(int $id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        // Password validation only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            // Update roles
            $roles = $this->request->getPost('roles') ?? [];
            $this->userModel->syncRoles($id, $roles);

            return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update user.');
    }

    /**
     * Delete user
     */
    public function delete(int $id)
    {
        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot delete your own account.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully.');
        }

        return redirect()->to('/admin/users')->with('error', 'Failed to delete user.');
    }
}
