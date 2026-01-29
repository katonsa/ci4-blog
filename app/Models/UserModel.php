<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Summary of getRoles
     * @param int $userId
     * @return array
     */
    public function getRoles(int $userId): array
    {
        return $this->db->table('roles_users')
            ->select('roles.id, roles.name')
            ->join('roles', 'roles.id = roles_users.role_id')
            ->where('roles_users.user_id', $userId)
            ->get()
            ->getResultArray();
    }

    /**
     * Syncs user roles (removes old, adds new).
     *
     * @param int $userId
     * @param array $roleIds Array of Role IDs
     * @return bool
     */
    public function syncRoles(int $userId, array $roleIds): bool
    {
        $builder = $this->db->table('roles_users');

        // Remove all existing roles for this user
        $builder->where('user_id', $userId)->delete();

        // Insert new roles
        if (empty($roleIds)) {
            return true;
        }

        $data = [];
        foreach ($roleIds as $roleId) {
            $data[] = [
                'user_id' => $userId,
                'role_id' => (int) $roleId,
            ];
        }

        return $builder->insertBatch($data) > 0;
    }
}
