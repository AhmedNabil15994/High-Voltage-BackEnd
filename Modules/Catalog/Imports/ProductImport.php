<?php


namespace Modules\Catalog\Imports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Catalog\Repositories\Dashboard\ProductRepository;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Enums\ProductFlag;
use Modules\Vendor\Entities\Vendor;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    private $request;
    private $repo;

    public function __construct(Request $request)
    {
        $this->repo = new ProductRepository();
        $this->request = $this->requestInitializing($request);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "*.{$this->request['status']}" => 'in:on,off|nullable',
            "*.{$this->request['qty']}" => 'nullable|integer',
            "*.{$this->request['price']}" => 'required|numeric|min:0',
            "*.{$this->request['title_ar']}" => 'required',
            "*.{$this->request['offer_price']}" => 'nullable|numeric|min:0',
            "*.{$this->request['offer_start_at']}" => 'required_with:offer_price',
            "*.{$this->request['offer_end_at']}" => 'required_with:offer_price',
            "*.{$this->request['sku']}" => 'nullable',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            "*.{$this->request['status']}.in" => 'status must in on or off',
            "*.{$this->request['qty']}.integer" => __('catalog::dashboard.products.validation.qty.integer'),
            "*.{$this->request['qty']}.min" => __('catalog::dashboard.products.validation.qty.min') . ' 0',
            "*.{$this->request['price']}.required" => __('catalog::dashboard.products.validation.price.required'),
            "*.{$this->request['price']}.numeric" => __('catalog::dashboard.products.validation.price.numeric'),
            "*.{$this->request['title_ar']}.required" => __('catalog::dashboard.products.validation.title.required'),
        ];
    }

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     * @throws \Exception
     */
    public function model(array $row)
    {
        $request = new Request();

        if (isset($row[$this->request['category']]) && $row[$this->request['category']]) {
            $categoriesNames = explode(',', $row[$this->request['category']]);
            $categories = Category::whereIn('title->ar', $categoriesNames)->orWhereIn('title->en', $categoriesNames)->pluck('id')->toArray();
        } else {
            $categories = $this->request['category_id'];
        }

        if (isset($row[$this->request['vendor']]) && $row[$this->request['vendor']]) {
            $vendorId = Vendor::where('title->ar', $row[$this->request['vendor']])->orWhere('title->en', $row[$this->request['vendor']])->first()->id ?? 1;
        }

        $request->replace([
            'product_flag' => ProductFlag::Single,
            // 'pending_for_approval' => 1,
            'category_id' => $categories && count($categories) ?  $categories : [1],
            // 'vendor_id' => $vendorId,
            // 'imported_excel' => 1,
            'qty' => $this->request['qty'] ?
                $row[$this->request['qty']] : null,
            'status' => $this->request['status'] ?
                $row[$this->request['status']] : null,
            'title' => [
                'en' => $this->request['title_en'] ?
                    $row[$this->request['title_en']] : '',
                'ar' => $this->request['title_ar'] ?
                    $row[$this->request['title_ar']] : '',
            ],
            'description' => [
                'en' => $this->request['description_en'] ?
                    $row[$this->request['description_en']] : '',
                'ar' => $this->request['description_ar'] ?
                    $row[$this->request['description_ar']] : '',
            ],
            'price' => $row[$this->request['price']],
            'sku' => $this->request['sku'] ? $row[$this->request['sku']] : null,
            'offer_status' => isset($this->request['offer_price']) && isset($this->request['start_at']) && isset($this->request['end_at']) ?
                'on' : null,
            'offer_price' => $this->request['offer_price'] ? $row[$this->request['offer_price']] : null,
            'start_at' => $this->request['offer_start_at'] ? Carbon::parse($row[$this->request['offer_start_at']])->toDateString() : null,
            'end_at' => $this->request['offer_end_at'] ? Carbon::parse($row[$this->request['offer_end_at']])->toDateString() : null,
        ]);

        $model = $request->sku ? $this->repo->findBySku($request->sku) : null;
        if ($model) {
            return $this->repo->update($request, $model->id);
        } else {
            return $this->repo->create($request);
        }
    }

    private function requestInitializing($oldRequest)
    {
        $request = [];
        foreach ($oldRequest->all() as $key => $value) {
            $request[$key] = str_replace(' ', '_', strtolower($oldRequest[$key])) ?? null;
        }

        return $request;
    }
}
