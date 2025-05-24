<?php

namespace Modules\Catalog\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalog\Enums\ProductFlag;

class ProductRequest extends FormRequest
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
                $rules = [
                    'product_flag' => 'required|in:' . implode(',', ProductFlag::getConstList()),
                    'title.*' => 'required',
                    'image' => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                    'category_id' => 'required',
                    'product_addon_types.price.*' => 'required|numeric|min:0',
                    'product_addon_types.qty.*' => 'nullable|integer|min:0',
                    'product_custom_addons' => 'required|array|min:1',
                ];
                return $rules;

            //handle updates
            case 'put':
            case 'PUT':
                $rules = [
                    'product_flag' => 'required|in:' . implode(',', ProductFlag::getConstList()),
                    'title.*' => 'required',
                    'image' => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                    'category_id' => 'required',
                    'product_addon_types.price.*' => 'required|numeric|min:0',
                    'product_addon_types.qty.*' => 'nullable|integer|min:0',
                    'product_custom_addons' => 'required|array|min:1',
                ];
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
        $v = [
            'image.required' => __('catalog::dashboard.products.validation.image.required'),
            'image.image' => __('catalog::dashboard.products.validation.image.image'),
            'image.mimes' => __('catalog::dashboard.products.validation.image.mimes'),
            'image.max' => __('catalog::dashboard.products.validation.image.max'),
        ];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v['title.' . $key . '.required'] = __('catalog::dashboard.products.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('catalog::dashboard.products.validation.title.unique') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
