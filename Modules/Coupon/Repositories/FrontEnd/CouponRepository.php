<?php

namespace Modules\Coupon\Repositories\FrontEnd;

use Modules\Coupon\Entities\Coupon;

class CouponRepository
{
    protected $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function getActiveCoupons($request)
    {        
        return $this->coupon->active()->unexpired()->get();
    }

}
