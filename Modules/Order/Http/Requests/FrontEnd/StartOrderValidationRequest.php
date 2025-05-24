<?php

namespace Modules\Order\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class StartOrderValidationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'address_id' => 'required|exists:addresses,id',
            'accept_terms_conditions' => 'required',
            'receiving_date' => 'required|date_format:Y-m-d',
            'delivery_date' => 'required|date_format:Y-m-d|after_or_equal:receiving_date',
            'receiving_time' => 'required|string',
            'delivery_time' => 'required|string',
            'order_type' => 'required|in:direct_with_pieces,direct_without_pieces',
            'notes' => 'nullable|string|max:3000',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!is_null($this->receiving_date)) {
                if ($this->receiving_date < date('Y-m-d')) {
                    return $validator->errors()->add(
                        'receiving_date', __('Pick-up day is not available, choose another day')
                    );
                }
            }

            if (!is_null($this->delivery_date)) {
                if ($this->delivery_date < date('Y-m-d')) {
                    return $validator->errors()->add(
                        'delivery_date', __('Delivery day is not available, choose another day')
                    );
                }
            }

            if (!is_null($this->receiving_time) && $this->receiving_date == date('Y-m-d')) {
                $timeFrom = explode('-', $this->receiving_time)[0];
                $timeTo = explode('-', $this->receiving_time)[1];

                if (now()->format('H:i') >= $timeTo) {
                    return $validator->errors()->add(
                        'receiving_time', __('Pick-up time is currently not available, choose another time')
                    );
                }
            }

            if (!is_null($this->delivery_time) && $this->delivery_date == date('Y-m-d')) {
                $timeFrom = explode('-', $this->delivery_time)[0];
                $timeTo = explode('-', $this->delivery_time)[1];

                if (now()->format('H:i') >= $timeTo) {
                    return $validator->errors()->add(
                        'delivery_time', __('Delivery time is currently not available, choose another time')
                    );
                }
            }

        });
        return true;
    }

}
