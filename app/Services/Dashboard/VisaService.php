<?php

namespace App\Services\Dashboard;

use App\Models\Visa;
use App\Models\VisaRequest;
use DB;

/**
 * Class VisaService.
 */
class VisaService
{
    public function list()
    {
        return Visa::with(['requiredFields'])
            ->get();
    }

    public function create(array $data): Visa
    {
        return DB::transaction(function () use ($data) {

            $visa = Visa::create([
                'duration' => $data['duration'],
                'price' => $data['price'],
            ]);

            $visa->translations()->create([
                'locale' => 'ar',
                'name' => $data['name_ar'],
                'description' => $data['description_ar'] ?? null,
            ]);

            $visa->translations()->create([
                'locale' => 'en',
                'name' => $data['name_en'],
                'description' => $data['description_en'] ?? null,
            ]);

            $this->syncRequiredFields($visa, $data['required_fields'] ?? []);

            return $visa->load(['requiredFields',]);
        });
    }

    public function update(Visa $visa, array $data): Visa
    {
        return DB::transaction(function () use ($visa, $data) {

            $visa->update([
                /*   'name' => $data['name'] ?? $visa->name,
                  'description' => $data['description'] ?? $visa->description, */
                'price' => $data['price'] ?? $visa->price,
                'duration' => $data['duration'] ?? $visa->duration
            ]);

            isset($data['name_en']) ?? $visa->translateOrNew('en')->name = $data['name_en'];
            isset($data['description_en']) ?? $visa->translateOrNew('en')->name = $data['description_en'];

            isset($data['name_ar']) ?? $visa->translateOrNew('ar')->name = $data['name_ar'];
            isset($data['description_ar']) ?? $visa->translateOrNew('ar')->name = $data['description_ar'];

            if (isset($data['required_fields'])) {
                $this->syncRequiredFields($visa, $data['required_fields']);
            }

            $visa->save();
            return $visa->load(['requiredFields',]);
        });
    }

    public function delete(Visa $visa): void
    {
        $visa->delete();
    }


    //: Helper Method
    protected function syncRequiredFields(Visa $visa, array $fields): void
    {
        $existingIds = collect($fields)
            ->pluck('id')
            ->filter()
            ->toArray();

        $visa->requiredFields()
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($fields as $field) {

            if (isset($field['id'])) {
                $visa_field = $visa->requiredFields()
                    ->where('id', $field['id'])->first();

                $visa_field->update([
                    'key' => $field['key'],
                    'type' => $field['type'],
                    'is_file' => $field['is_file'],
                    'is_required' => $field['is_required'],
                ]);

                isset($field['label_ar']) ?? $visa_field->translateOrNew('ar')->label = $field['label_ar'];
                isset($field['label_en']) ?? $visa_field->translateOrNew('en')->label = $field['label_en'];

                $visa_field->save();
            } else {
                $visa_field = $visa->requiredFields()->create([
                    'key' => $field['key'],
                    'type' => $field['type'],
                    'is_file' => $field['is_file'],
                    'is_required' => $field['is_required'],
                ]);
                $visa_field->translation()->create([
                    'locale' => 'ar',
                    'label' => $field['label_ar']
                ]);
                $visa_field->translation()->create([
                    'locale' => 'en',
                    'label' => $field['label_en']
                ]);
                $visa_field->save();

            }
        }
    }


}
