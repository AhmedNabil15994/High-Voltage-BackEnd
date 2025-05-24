<?php

namespace Modules\Baqat\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Baqat\Http\Requests\Dashboard\BaqatSubscriptionRequest;
use Modules\Baqat\Repositories\Dashboard\BaqatSubscriptionRepository as BaqatSubscription;
use Modules\Baqat\Transformers\Dashboard\BaqatSubscriptionResource;
use Modules\Core\Traits\DataTable;

class BaqatSubscriptionController extends Controller
{
    protected $baqatSubscription;

    public function __construct(BaqatSubscription $baqatSubscription)
    {
        $this->baqatSubscription = $baqatSubscription;
    }

    public function index()
    {
        return view('baqat::dashboard.baqat_subscriptions.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->baqatSubscription->QueryTable($request));
        $datatable['data'] = BaqatSubscriptionResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function currentIndex()
    {
        return view('baqat::dashboard.baqat_subscriptions.current.index');
    }

    public function currentDatatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->baqatSubscription->successQueryTable($request));
        $datatable['data'] = BaqatSubscriptionResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('baqat::dashboard.baqat_subscriptions.create');
    }

    public function store(BaqatSubscriptionRequest $request)
    {
        try {
            $create = $this->baqatSubscription->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function edit($id)
    {
        $baqatSubscription = $this->baqatSubscription->findById($id, ['baqa', 'user']);
        if (!$baqatSubscription) {
            abort(404);
        }
        return view('baqat::dashboard.baqat_subscriptions.edit', compact('baqatSubscription'));
    }

    public function show($id)
    {
        $baqatSubscription = $this->baqatSubscription->findById($id, ['baqa', 'user', 'transaction']);
        if (!$baqatSubscription) {
            abort(404);
        }
        return view('baqat::dashboard.baqat_subscriptions.show', compact('baqatSubscription'));
    }

    public function update(BaqatSubscriptionRequest $request, $id)
    {
        try {
            $update = $this->baqatSubscription->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->baqatSubscription->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            if (empty($request['ids'])) {
                return Response()->json([false, __('apps::dashboard.general.select_at_least_one_item')]);
            }

            $deleteSelected = $this->baqatSubscription->deleteSelected($request);
            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
