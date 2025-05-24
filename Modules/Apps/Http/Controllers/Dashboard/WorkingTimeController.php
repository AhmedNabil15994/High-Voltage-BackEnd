<?php

namespace Modules\Apps\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Apps\Http\Requests\Dashboard\WorkingTimesRequest;
use Modules\Apps\Repositories\Dashboard\WorkingTimeRepository as WorkingTimeRepo;

class WorkingTimeController extends Controller
{
    protected $workingTime;

    public function __construct(WorkingTimeRepo $workingTime)
    {
        $this->workingTime = $workingTime;
    }

    public function index(Request $request)
    {
        $pickupWorkingDays = $this->workingTime->getAllPickupWorkingDays();
        $deliveryWorkingDays = $this->workingTime->getAllDeliveryWorkingDays();
        return view('apps::dashboard.working_times.index', compact('pickupWorkingDays', 'deliveryWorkingDays'));
    }

    public function store(WorkingTimesRequest $request)
    {
        try {
            $model = $this->workingTime->save($request);
            if ($model) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }
            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->getMessage()]);
        }
    }
}
