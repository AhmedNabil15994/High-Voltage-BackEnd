<?php

namespace Modules\Company\Repositories\FrontEnd;

use Modules\Company\Entities\DeliveryCharge;

class DeliveryChargeRepository
{
    protected $deliveryCharge;

    public function __construct(DeliveryCharge $deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;
    }

    public function findByStateId($stateId)
    {
        return $this->deliveryCharge->active()->filterState($stateId)->first();
    }

    public function findByStateAndCompany($stateId, $companyId)
    {
        return $this->deliveryCharge->active()->filterState($stateId)->where('company_id', $companyId)->first();
    }

}
