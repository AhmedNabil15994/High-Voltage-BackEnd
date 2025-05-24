<?php

namespace Modules\Baqat\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaqatRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            // handle creates
            case 'post':
            case 'POST':
            case 'put':
            case 'PUT':
                $rules = [
                    'title.*' => 'required|max:290',
                    'description.*' => 'required|max:3000',
                    'duration_description.*' => 'required|max:3000',
                    'duration_by_days' => 'required|numeric|min:1',
                    'price' => 'required|numeric|min:0',
                    'client_price' => 'required|numeric|min:0',
                    'sort' => 'nullable|numeric',
                ];

                if ($this->offer_status) {
                    $rules['offer_type'] = 'required|in:amount,percentage';
                    $rules['offer_price'] = 'required_if:offer_type,amount';
                    $rules['offer_percentage'] = 'required_if:offer_type,percentage';
                    $rules['start_at'] = 'required_if:offer_status,on|date';
                    $rules['end_at'] = 'required_if:offer_status,on|date';
                }

                return $rules;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $v = [];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v["title." . $key . ".required"] = __('baqat::dashboard.baqat.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique_translation"] = __('baqat::dashboard.baqat.validation.title.unique') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
