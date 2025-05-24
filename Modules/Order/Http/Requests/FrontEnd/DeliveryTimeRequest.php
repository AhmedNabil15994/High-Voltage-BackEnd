<?php

namespace Modules\Order\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryTimeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'selected_pickup_receiving_date' => 'required|date_format:Y-m-d',
            'selected_pickup_receiving_time' => 'required|string',
        ];
        return $rules;
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
        return [];
    }

}
