<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SupervisorCreateRequest;
use App\Http\Requests\Dashboard\SupervisorUpdateRequest;
use App\Models\User;
use App\Services\Dashboard\AdministrationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AdministrationController extends Controller
{

    use ResponseTrait;


    public function __construct(public AdministrationService $service)
    {
    }

    public function index()
    {
        $supervisors = User::role('supervisor', 'api')->get()
            ->append(['role_name', 'permissions_names'])
            ->makeHidden(['roles', 'permissions']);

        return $this->showResponse($supervisors, 'تم جلب كل المشرفين بنجاح', 200);
    }


    public function show(string $id)
    {
        try {
            $supervisor = User::findOrFail($id)
                ->append(['role_name', 'permissions_names'])
                ->makeHidden(['roles', 'permissions']);

            return $this->showResponse($supervisor, 'تم جلب كل المشرف بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء تسجيل الحساب');
        }
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(SupervisorCreateRequest $adminCreateRequest)
    {
        try {
            $admin = $this->service->store($adminCreateRequest);
            return $this->showResponse($admin, 'تم تسجيل الحساب بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء تسجيل الحساب');
        }
    }


    /**
     * Update the resource in storage.
     */
    public function update(SupervisorUpdateRequest $supervisorUpdateRequest, string $id)
    {
        try {
            $supervisor = User::findOrFail($id);
            $user = $this->service->update($supervisor, $supervisorUpdateRequest);
            return $this->showResponse($user, 'تم تعديل البيانات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء التعديل على بيانات المشرف');

        }

    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supervisor = User::findOrFail($id);
            $supervisor->delete();
            return $this->showMessage('تم حذف حساب المشرف بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف المشرف');
        }
    }

    /**
     * List all of Permissions
     * 
     * the list can be use to assign permissions to a supervisor
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexPermissions()
    {
        return $this->showResponse(Permission::query()->get(['id', 'name']), 'تم جلب كل الصلاحيات', 200);
    }
}
