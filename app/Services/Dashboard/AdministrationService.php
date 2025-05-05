<?php

namespace App\Services\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class AdministrationService.
 */
class AdministrationService
{

    public function store(FormRequest $formRequest)
    {
        $user = User::create($formRequest->only(['name', 'email', 'phone_number', 'password']));
        $user->markEmailAsVerified();
        $user->assignRole(Role::where('name', 'supervisor')->first());
        $permissions = [];
        $permissionIds = $formRequest->input('permissions', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $user->givePermissionTo($permissions);
        $user->load(['roles', 'permissions']);
        $user->append(['role_name', 'permissions_names']);
        $user->makeHidden(['roles', 'permissions']);
        return $user;
    }
}
