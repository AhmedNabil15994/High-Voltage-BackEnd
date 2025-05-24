<?php

namespace Modules\Apps\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class DeliveryWorkingDay extends Model
{
    use HasTranslations, ScopesTrait;

    protected $with = ['deliveryWorkingTimes'];
    protected $guarded = ['id'];
    public $translatable = ['day_name'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function deliveryWorkingTimes()
    {
        return $this->hasMany(DeliveryWorkingTime::class, 'delivery_working_day_id');
    }
}
