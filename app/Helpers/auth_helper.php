<?php

use App\Models\UserModel;

/**
 * Check if current user has the given role
 *
 * @param string $role Role name to check
 * @return bool
 */
function has_role(string $role): bool
{
    $userId = session()->get('user_id');

    if (!$userId) {
        return false;
    }

    $userModel = new UserModel();
    $userRoles = $userModel->getRoles($userId);
    $roleNames = array_column($userRoles, 'name');

    return in_array($role, $roleNames);
}

/**
 * Check if current user has any of the given roles
 *
 * @param array $roles Array of role names to check
 * @return bool
 */
function has_any_role(array $roles): bool
{
    foreach ($roles as $role) {
        if (has_role($role)) {
            return true;
        }
    }

    return false;
}

/**
 * Check if current user is an admin
 *
 * @return bool
 */
function is_admin(): bool
{
    return has_role('admin');
}

/**
 * Check if current user is a writer
 *
 * @return bool
 */
function is_writer(): bool
{
    return has_role('writer');
}
