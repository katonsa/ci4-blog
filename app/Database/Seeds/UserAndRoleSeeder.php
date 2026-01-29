<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use App\Models\RoleModel;
use CodeIgniter\Database\Seeder;

class UserAndRoleSeeder extends Seeder
{
    public function run()
    {
        $roleModel = new RoleModel();
        $userModel = new UserModel();

        // Create roles
        $roles = [
            [
                'name'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'writer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($roles as $role) {
            $roleModel->insert($role);
        }

        // Get role IDs by name
        $adminRole = $roleModel->where('name', 'admin')->first();
        $writerRole = $roleModel->where('name', 'writer')->first();

        // Define users
        $users = [
            [
                'name'       => 'Admin User',
                'email'      => 'admin@example.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Writer User',
                'email'      => 'writer@example.com',
                'password'   => password_hash('writer123', PASSWORD_DEFAULT),
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($users as $user) {
            $userModel->insert($user);
        }

        // Get user IDs by email
        $adminUser = $userModel->where('email', 'admin@example.com')->first();
        $writerUser = $userModel->where('email', 'writer@example.com')->first();

        // Assign roles using UserModel's syncRoles method
        $userModel->syncRoles($adminUser['id'], [$adminRole['id']]);
        $userModel->syncRoles($writerUser['id'], [$writerRole['id']]);
    }
}
