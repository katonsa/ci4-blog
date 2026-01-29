<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'slug', 'content', 'author_id', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title'   => 'required|min_length[3]|max_length[255]',
        'content' => 'required',
        'status'  => 'permit_empty|in_list[published,draft]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateSlug'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['generateSlug'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Generate slug from title before insert/update
     */
    protected function generateSlug(array $data): array
    {
        if (isset($data['data']['title'])) {
            $title = $data['data']['title'];
            $slug = url_title($title, '-', true);

            // Check if slug exists, append number if needed
            $count = $this->where('slug', $slug)->countAllResults(false);

            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }

            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    /**
     * Get published posts
     */
    public function getPublished(int $limit = 0, int $offset = 0): array
    {
        $builder = $this->where('status', 'published')
            ->orderBy('created_at', 'DESC');

        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->find();
    }

    /**
     * Get posts by author
     */
    public function getByAuthor(int $authorId): array
    {
        return $this->where('author_id', $authorId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get post with author info
     */
    public function getWithAuthor(int $id): ?array
    {
        return $this->select('posts.*, users.name as author_name')
            ->join('users', 'users.id = posts.author_id')
            ->where('posts.id', $id)
            ->first();
    }

    /**
     * Get recent posts with author info
     */
    public function getRecentWithAuthor(int $limit = 5): array
    {
        return $this->select('posts.*, users.name as author_name')
            ->join('users', 'users.id = posts.author_id')
            ->orderBy('posts.created_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Count posts by status
     */
    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }
}
