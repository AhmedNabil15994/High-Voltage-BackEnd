<?php

namespace Modules\Catalog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDeliveryChargeCondition
{
    public function handle(Request $request, Closure $next)
    {
        if (is_null(getCartConditionByName(null, 'company_delivery_fees'))) {
            return redirect()->route('frontend.order_request.start')->withErrors(__('The data must be filled in first'));
        }
        return $next($request);
    }
}
