<?php

namespace App\Database\Seeds;

use App\Models\PostModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $postModel = new PostModel();
        $userModel = new UserModel();

        // Get first user as author
        $author = $userModel->first();

        if (!$author) {
            echo "No users found. Please run UserAndRoleSeeder first.\n";
            return;
        }

        $posts = [
            [
                'title'     => 'Welcome to Your New Blog',
                'content'   => 'This is your first blog post. You can edit or delete it, and start creating your own content!',
                'author_id' => $author['id'],
                'status'    => 'published',
            ],
            [
                'title'     => 'Getting Started with CodeIgniter 4',
                'content'   => 'CodeIgniter 4 is a powerful PHP framework with minimal footprint, built for developers who need a simple and elegant toolkit to create full-featured web applications.',
                'author_id' => $author['id'],
                'status'    => 'published',
            ],
            [
                'title'     => 'Draft Post - Work in Progress',
                'content'   => 'This is a draft post. It will not be visible on the public site until you publish it.',
                'author_id' => $author['id'],
                'status'    => 'draft',
            ],
        ];

        foreach ($posts as $post) {
            $postModel->insert($post);
        }

        echo "Posts seeded successfully.\n";
    }
}
