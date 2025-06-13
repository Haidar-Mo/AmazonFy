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

    public function store(FormRequest $request)
    {
        $data = $request->validated();

        if (key_exists('email', $data) && $data['email'] == null)
            $data['email'] = '';
        if (key_exists('phone_number', $data) && $data['phone_number'] == null)
            $data['phone_number'] = '';

        $user = User::create($data);
        $user->markEmailAsVerified();
        $user->assignRole(Role::where('name', 'supervisor')->first());
        $permissions = [];
        $permissionIds = $request->input('permissions', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $user->givePermissionTo($permissions);
        $user->append(['role_name', 'permissions_names']);
        $user->makeHidden(['roles', 'permissions']);
        return $user;
    }

    public function update(User $supervisor, FormRequest $request)
    {
        $request->validated();
        $supervisor->update($request->only(['name', 'email', 'phone_number', 'password']));
        $permissions = [];
        $permissionIds = $request->input('permissions', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        if ($permissions->count() > 0)
            $supervisor->syncPermissions($permissions);
        $supervisor->append(['role_name', 'permissions_names']);
        $supervisor->makeHidden(['roles', 'permissions']);
        return $supervisor;

    }
}
