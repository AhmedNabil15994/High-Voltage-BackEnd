<?php

namespace Modules\Baqat\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class BaqatOffer extends Model
{
    use ScopesTrait;

    public $table = "baqat_offers";
    protected $guarded = ["id"];

    public function scopeUnexpired($query)
    {
        return $query->where('start_at', '<=', date('Y-m-d'))->where('end_at', '>', date('Y-m-d'));
    }

    public function baqa()
    {
        return $this->belongsTo(Baqat::class, 'baqat_id');
    }

}
