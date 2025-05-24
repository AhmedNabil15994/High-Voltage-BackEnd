<?php

namespace Modules\Baqat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\HasSlugTranslation;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class Baqat extends Model
{
    use HasSlugTranslation;
    use HasTranslations, SoftDeletes, ScopesTrait;

    public $table = "baqat";
    protected $guarded = ["id"];
    public $translatable = ['title', 'slug', 'description', 'duration_description'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    
    public function baqatSubscriptions()
    {
        return $this->hasMany(BaqatSubscription::class, 'baqat_id');
    }

    public function offer()
    {
        return $this->hasOne(BaqatOffer::class, 'baqat_id');
    }
}
