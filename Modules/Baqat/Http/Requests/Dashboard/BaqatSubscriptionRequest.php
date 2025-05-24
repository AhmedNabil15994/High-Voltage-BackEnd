<?php

namespace Modules\Baqat\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaqatSubscriptionRequest extends FormRequest
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
                return [
                    'baqat_id' => 'required|exists:baqat,id',
                    'user_id' => 'required|exists:users,id',
                    // 'start_at' => 'required|date_format:Y-m-d',
                ];
            case 'put':
            case 'PUT':
                return [
                    // 'start_at' => 'required|date_format:Y-m-d',
                ];
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
        return [];
    }
}
