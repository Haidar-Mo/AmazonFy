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
            $regions = Region::where('locale', '=', app()->getLocale())
                ->with(['children'])
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
            $regions = Region::where('locale', '=', app()->getLocale())
                ->with(['children'])
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
                'name' => 'required|string'
            ]);
            $region = Region::create($data);
            return $this->showResponse($region, 'region.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.create_error');
        }
    }
    public function localeStore(Request $request)
    {
        try {
            $request->validate([
                'parent_id' => 'nullable|exists:regions,id',
                'regions' => 'required|array',
                'region.*.locale' => 'required|string',
                'region.*.name' => 'required|string'
            ]);
            foreach ($request->regions as $region) {
                $regions[] = Region::create([
                    'parent_id' => $request->parent_id,
                    'locale' => $region['locale'],
                    'name' => $region['name'],
                ]);
            }
            return $this->showResponse($regions, 'region.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'region.errors.create_error');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'parent_id' => 'sometimes|exists:regions,id',
                'name' => 'sometimes|string'
            ]);
            $region = Region::findOrFail($id);
            $region->update($data);
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
