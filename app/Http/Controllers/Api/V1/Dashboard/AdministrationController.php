<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AdminCreateRequest;
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


    /**
     * Store the newly created resource in storage.
     */
    public function store(AdminCreateRequest $adminCreateRequest)
    {
        try {
            $admin = $this->service->store($adminCreateRequest);
            return $this->showResponse($admin, 'تم تسجيل الحساب بنجاح', 200);
        } catch (\Exception $e) {
            report($e);
            return $this->showError($e, 'حدث خطأ أثناء تسجيل الحساب');
        }
    }


    /**
     * Update the resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supervisor = User::role('supervisor')->find($id);

    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supervisor = User::role('supervisor', 'api')->findOrFail($id);
            $supervisor->delete();
            return $this->showMessage('تم حذف حساب المشرف بنجاح', 200);
        } catch (\Exception $e) {
            report($e);
            return $this->showError($e, 'حدث خطأ ما أثناء حذف المشرف');
        }
    }


    public function indexPermissions()
    {
        return $this->showResponse(Permission::query()->get(['id', 'name']), 'تم جلب كل الصلاحيات', 200);
    }
}
