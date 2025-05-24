<?php

namespace Modules\Apps\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class PickupWorkingDay extends Model
{
    use HasTranslations, ScopesTrait;

    protected $with = ['pickupWorkingTimes'];
    protected $guarded = ['id'];
    public $translatable = ['day_name'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function pickupWorkingTimes()
    {
        return $this->hasMany(PickupWorkingTime::class, 'pickup_working_day_id');
    }
}
