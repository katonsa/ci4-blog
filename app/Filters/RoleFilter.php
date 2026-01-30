<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Check if user has required role
     *
     * Usage in routes: $routes->add('/admin/users', ['filter' => 'role:admin'])
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        if (empty($arguments)) {
            return;
        }

        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();

        // Get user roles
        $userRoles = $userModel->getRoles($userId);
        $roleNames = array_column($userRoles, 'name');

        // Check if user has any of the required roles
        foreach ($arguments as $requiredRole) {
            if (in_array($requiredRole, $roleNames)) {
                return; // User has required role
            }
        }

        // User doesn't have required role
        return redirect()->to('/admin')
            ->with('error', 'You do not have permission to access this page.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
