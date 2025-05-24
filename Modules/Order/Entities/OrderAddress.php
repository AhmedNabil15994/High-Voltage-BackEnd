<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Address;

class OrderAddress extends Model
{
    protected $guarded = ['id'];

    public function state()
    {
        return $this->belongsTo(\Modules\Area\Entities\State::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
