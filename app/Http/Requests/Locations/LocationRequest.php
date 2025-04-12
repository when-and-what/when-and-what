<?php

namespace App\Http\Requests\Locations;

use App\Models\Locations\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user_id = $this->user()->id;

        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required',
            'category' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) use ($user_id) {
                    $ids = [];
                    foreach ($value as $v) {
                        if (! is_numeric($v)) {
                            $fail($attribute.' must be a valid category');
                        } else {
                            $ids[] = $v;
                        }
                    }
                    $check = Category::where('user_id', $user_id)
                        ->whereIn('id', $ids)
                        ->get();
                    if (count($check) != count($value)) {
                        $fail($attribute.' must be a valid category');
                    }
                },
                // 'exists:location_categories,id',
                // Rule::exists('location_categories,id')->where(function ($query) use ($user_id) {
                // return $query->where('user_id', $user_id);
                // }),
            ],
        ];
    }
}
