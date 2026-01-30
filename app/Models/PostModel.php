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
        if (!isset($data['data']['title'])) {
            return $data;
        }

        $title = $data['data']['title'];
        $baseSlug = url_title($title, '-', true);
        $currentId = $this->extractId($data);

        if ($currentId !== null) {
            $current = $this->find($currentId);
            if ($current && ($current['title'] ?? null) === $title) {
                $data['data']['slug'] = $current['slug'];
                return $data;
            }
        }

        $data['data']['slug'] = $this->uniqueSlug($baseSlug, $currentId);

        return $data;
    }

    private function extractId(array $data): ?int
    {
        if (!isset($data['id'])) {
            return null;
        }

        $id = $data['id'];

        if (is_array($id)) {
            $id = $id[0] ?? null;
        }

        if ($id === null) {
            return null;
        }

        return (int) $id;
    }

    private function uniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $builder = $this->builder();
        $builder->select('slug');
        $builder->like('slug', $baseSlug, 'after');

        if ($ignoreId !== null) {
            $builder->where($this->primaryKey . ' !=', $ignoreId);
        }

        $existing = array_column($builder->get()->getResultArray(), 'slug');

        if (!in_array($baseSlug, $existing, true)) {
            return $baseSlug;
        }

        $maxSuffix = 1;
        $pattern = '/^' . preg_quote($baseSlug, '/') . '-(\d+)$/';

        foreach ($existing as $slug) {
            if (preg_match($pattern, $slug, $matches)) {
                $maxSuffix = max($maxSuffix, (int) $matches[1]);
            }
        }

        return $baseSlug . '-' . ($maxSuffix + 1);
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
