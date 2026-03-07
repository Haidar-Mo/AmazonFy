<?php

namespace App\Services\Dashboard;

use App\Models\AirLine;
use DB;

/**
 * Class AirLineService.
 */
class AirLineService
{

    public function list()
    {
        return AirLine::with(['requiredFields'])
            ->get();
    }

    public function create(array $data): AirLine
    {
        return DB::transaction(function () use ($data) {

            $airLine = AirLine::create([
                'iata_code' => $data['iata_code'],
                'is_active' => $data['is_active'],
            ]);

            $airLine->translations()->create([
                'locale' => 'ar',
                'name' => $data['name_ar'],
            ]);

            $airLine->translations()->create([
                'locale' => 'en',
                'name' => $data['name_en'],
            ]);

            $this->syncRequiredFields($airLine, $data['required_fields'] ?? []);

            return $airLine->load(['requiredFields',]);
        });
    }

    public function update(AirLine $airLine, array $data): AirLine
    {
        return DB::transaction(function () use ($airLine, $data) {

            $airLine->update([
                'iata_code' => $data['iata_code'] ?? $airLine->iata_code,
                'is_active' => $data['is_active'] ?? $airLine->is_active
            ]);

            isset($data['name_en']) ?? $airLine->translateOrNew('en')->name = $data['name_en'];
            isset($data['name_ar']) ?? $airLine->translateOrNew('ar')->name = $data['name_ar'];

            if (isset($data['required_fields'])) {
                $this->syncRequiredFields($airLine, $data['required_fields']);
            }

            $airLine->save();
            return $airLine->load(['requiredFields',]);
        });
    }

    public function delete(AirLine $airLine): void
    {
        $airLine->delete();
    }


    //: Helper Method
    protected function syncRequiredFields(AirLine $airLine, array $fields): void
    {
        $existingIds = collect($fields)
            ->pluck('id')
            ->filter()
            ->toArray();

        $airLine->requiredFields()
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($fields as $field) {

            if (isset($field['id'])) {
                $airLine_field = $airLine->requiredFields()
                    ->where('id', $field['id'])->first();

                $airLine_field->update([
                    'key' => $field['key'],
                    'type' => $field['type'],
                    'is_file' => $field['is_file'],
                    'is_required' => $field['is_required'],
                ]);

                isset($field['label_ar']) ?? $airLine_field->translateOrNew('ar')->label = $field['label_ar'];
                isset($field['label_en']) ?? $airLine_field->translateOrNew('en')->label = $field['label_en'];

                $airLine_field->save();
            } else {
                $airLine_field = $airLine->requiredFields()->create([
                    'key' => $field['key'],
                    'type' => $field['type'],
                    'is_file' => $field['is_file'],
                    'is_required' => $field['is_required'],
                ]);
                $airLine_field->translation()->create([
                    'locale' => 'ar',
                    'label' => $field['label_ar']
                ]);
                $airLine_field->translation()->create([
                    'locale' => 'en',
                    'label' => $field['label_en']
                ]);
                $airLine_field->save();

            }
        }
    }
}
