<?php

namespace Modules\Report\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Order\Entities\Order;
use Modules\Order\Transformers\Dashboard\OrderResource;
use Modules\Report\Repositories\Dashboard\ReportRepository as Repo;
use Modules\Report\Transformers\Dashboard\LateOrderResource;
use Modules\Report\Transformers\Dashboard\SubscriptionsStatusResource;

class ReportController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function getOrderReports(Request $request)
    {
        return view('report::dashboard.reports.index');
    }

    public function getLateOrderReports(Request $request)
    {
        return view('report::dashboard.reports._partial.late-orders');
    }

    public function getSubscriptionsStatusList(Request $request)
    {
        return view('report::dashboard.reports._partial.subscriptions');
    }

    public function getDeliveredOrdersReports(Request $request)
    {
        return view('report::dashboard.reports._partial.delivered-orders');
    }

    public function lateOrderDatatable(Request $request)
    {

        $collection = $this->repo->lateOrdersQueryTable($request)->get();
        $filteredCollection = $collection->reject(function ($value, $key) {
            $date = $value->orderTimes->delivery_data['delivery_date']??null;
            $dateFormat = $value->orderTimes->delivery_data['delivery_time_format_type'] ??'am';
            $time = explode(' - ', $value->orderTimes->delivery_data['delivery_time'])[1] ?? '00:00:00';
            $buildDate = $date . ' ' . $time . ' ' . $dateFormat;
            $buildDate = Carbon::createFromFormat('Y-m-d H:i a', $buildDate);
            $driverStatusDate = $value->driverOrderStatuses->first()->created_at;
            return $buildDate->gt($driverStatusDate);
        });

        $orderQuery = $filteredCollection->count() > 0 ? $filteredCollection->toQuery() : Order::query()->whereNull('id');
        $datatable = DataTable::drawTable($request, $orderQuery, 'orders');
        $datatable['data'] = LateOrderResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function subscriptionsStatusDatatable(Request $request)
    {
        $data = $this->repo->subscriptionsStatusQueryTable($request);
        $counts = [
            'sumActive' => $this->repo->subscriptionsStatusQueryTable($request)->SumActive(),
            'countActive' => $this->repo->subscriptionsStatusQueryTable($request)->CountActive(),
            'sumInActive' => $this->repo->subscriptionsStatusQueryTable($request)->SumInActive(),
            'countInActive' => $this->repo->subscriptionsStatusQueryTable($request)->CountInActive(),
        ];
        $datatable = DataTable::drawTable($request, $data);
        $datatable['data'] = SubscriptionsStatusResource::collection($datatable['data']);
        $datatable['counts'] = $counts;
        return Response()->json($datatable);
    }

    public function deliveredOrdersDatatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repo->deliveredOrdersQueryTable($request));
        $datatable['data'] = OrderResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

}
