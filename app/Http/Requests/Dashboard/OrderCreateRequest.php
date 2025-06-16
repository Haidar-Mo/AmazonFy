<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'region_id' => ['nullable', 'exists:regions,id'],
            'name' => ['required', 'string'],
            'email' => ['required_if:phone_number,null', 'email'],
            'phone_number' => ['required_if:email,null', 'string'],
            'address' => ['sometimes', 'string'],



            'orders' => ['required', 'array'],
            'orders.*.shop_id' => ['required', 'exists:shops,id'],
            'orders.*.product_id' => [
                'required',
                // Custom rule to check product exists in the specified shop
                function ($attribute, $value, $fail) {
                    // Extract the order index (e.g., "orders.0.product_id" → index 0)
                    $index = explode('.', $attribute)[1];

                    // Get the corresponding shop_id for this order item
                    $shopId = $this->input("orders.$index.shop_id");

                    // Check if the product exists in shop_products for this shop
                    $exists = DB::table('shop_products')
                        ->where('product_id', $value)
                        ->where('shop_id', $shopId)
                        ->exists();

                    if (!$exists) {
                        $fail("The product ID {$value} does not exist in shop {$shopId}.");
                    }
                },
            ],
            'orders.*.wholesale_price' => ['required', 'numeric'],
            'orders.*.selling_price' => ['required', 'numeric'],
            'orders.*.count' => ['required', 'integer'],
            'orders.*.customer_note' => ['required', 'string'],
        ];

    }
}
