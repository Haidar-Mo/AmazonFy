<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SupervisorCreateRequest;
use App\Http\Requests\Dashboard\SupervisorUpdateRequest;
use App\Models\User;
use App\Services\Dashboard\AdministrationService;
use App\Traits\ResponseTrait;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class AdministrationController extends Controller
{
    use ResponseTrait;

    public function __construct(public AdministrationService $service)
    {
    }

    public function index()
    {
        try {
            $supervisors = User::role('supervisor', 'api')->get()
                ->append(['role_name', 'permissions_names'])
                ->makeHidden(['roles', 'permissions']);

            return $this->showResponse(
                $supervisors,
                'administration.supervisor.index_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'administration.supervisor.errors.index_error'
            );
        }
    }

    public function show(string $id)
    {
        try {
            $supervisor = User::findOrFail($id)
                ->append(['role_name', 'permissions_names'])
                ->makeHidden(['roles', 'permissions']);

            return $this->showResponse(
                $supervisor,
                'administration.supervisor.show_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'administration.supervisor.errors.show_error',
                []
            );
        }
    }

    public function store(SupervisorCreateRequest $request)
    {
        try {
            $admin = $this->service->store($request);
            return $this->showResponse(
                $admin,
                'administration.supervisor.store_success',
                [],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'administration.supervisor.errors.store_error',
                []
            );
        }
    }

    public function update(SupervisorUpdateRequest $request, string $id)
    {
        try {
            $supervisor = User::findOrFail($id);
            $user = $this->service->update($supervisor, $request);
            return $this->showResponse(
                $user,
                'administration.supervisor.update_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'administration.supervisor.errors.update_error',
                []
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $supervisor = User::findOrFail($id);
            $supervisor->delete();
            return $this->showMessage(
                'administration.supervisor.delete_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'administration.supervisor.errors.delete_error',
                []
            );
        }
    }

    public function indexPermissions()
    {
        try {
            return $this->showResponse(
                Permission::all(),
                'administration.supervisor.permissions_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'administration.supervisor.errors.permissions_error'
            );
        }
    }
}