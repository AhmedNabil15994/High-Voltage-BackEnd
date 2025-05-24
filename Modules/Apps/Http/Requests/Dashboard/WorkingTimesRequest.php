<?php

namespace Modules\Apps\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class WorkingTimesRequest extends FormRequest
{
    public function rules()
    {
        return [];
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

    public function withValidator($validator)
    {
        $pickupDays = $this->selected_days['pickup'];
        $deliveryDays = $this->selected_days['delivery'];

        /* $pickupDays = collect($pickupDays)->reject(function ($item) {
        return !isset($item['status']) || (isset($item['status']) && $item['status'] == 0);
        })->toArray();

        $deliveryDays = collect($deliveryDays)->reject(function ($item) {
        return !isset($item['status']) || (isset($item['status']) && $item['status'] == 0);
        })->toArray(); */

        $validator->after(function ($validator) use ($pickupDays, $deliveryDays) {

            $pickupValidation = $this->buildCustomValidationMessage($pickupDays, 'pickup');
            $deliveryValidation = $this->buildCustomValidationMessage($deliveryDays, 'delivery');

            if (!is_null($pickupValidation)) {
                return $validator->errors()->add('working_times', $pickupValidation);
            }
            if (!is_null($deliveryValidation)) {
                return $validator->errors()->add('working_times', $deliveryValidation);
            }

        });

        // $this->request->add(['selected_days' => ['pickup' => $pickupDays, 'delivery' => $deliveryDays]]);
    }

    public function arrayContainsDuplicate($array)
    {
        return count($array) != count(array_unique($array));
    }

    private function buildCustomValidationMessage($days, $requestType)
    {
        $msg = null;
        foreach ($days as $key => $day) {
            if ($day['is_full_day'] == 0) {
                if (empty($day['times'])) {
                    $msg = __('apps::dashboard.working_days.validations.times.required');
                } else {

                    if ($this->arrayContainsDuplicate(array_column($day['times'], 'from')) && $this->arrayContainsDuplicate(array_column($day['times'], 'to'))) {
                        $msg = __('apps::dashboard.working_days.validations.times.duplicated_time', ['dayCode' => __('apps::frontend.days.' . $key), 'requestType' => $requestType]);
                    }

                    foreach ($day['times'] as $k => $time) {

                        if (strtotime($time['to']) < strtotime($time['from'])) {
                            $msg = __('apps::dashboard.working_days.validations.times.time_to_greater_than_time_from', ['timeTo' => $time['to'], 'dayCode' => __('apps::frontend.days.' . $key), 'timeFrom' => $time['from'], 'requestType' => $requestType]);
                        }

                    }
                }
            }
        }

        return $msg;
    }
}
