<?php
namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            $regions = Region::with(['children'])
                ->where('parent_id', null)
                ->get();
            return $this->showResponse($regions, 'region.index_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.index_error');
        }
    }

    public function show(string $id)
    {
        try {
            $regions = Region::with(['children'])
                ->find($id);
            return $this->showResponse($regions, 'region.show_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.show_error');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'parent_id' => 'nullable|exists:regions,id',
                'name_en' => 'required|string',
                'name_ar' => 'required|string',
            ]);
            $region = Region::create([
                'parent_id' => $data['parent_id']
            ]);
            $region->translations->createMany([
                [
                    'local' => 'en',
                    'name' => $data['name_en']
                ],
                [
                    'local' => 'ar',
                    'name' => $data['name_ar']

                ]
            ]);
            return $this->showResponse($region, 'region.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.create_error');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'parent_id' => 'sometimes|exists:regions,id',
                'name_ar' => 'sometimes|string',
                'name_en' => 'sometimes|string'
            ]);
            $region = Region::findOrFail($id);
            $region->update($request->only('parent_id'));

            if (isset($data['name_ar'])) {
                $region->translateOrNew('ar')->name = $data['name_ar'];
            }
            if (isset($data['name_en'])) {
                $region->translateOrNew('en')->name = $data['name_en'];
            }
            $region->save();
            return $this->showResponse($region, 'region.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.update_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            Region::findOrFail($id)->delete();
            return $this->showMessage('region.delete_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.delete_error');
        }
    }
}
