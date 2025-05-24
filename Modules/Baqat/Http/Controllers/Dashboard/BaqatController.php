<?php

namespace Modules\Baqat\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Baqat\Http\Requests\Dashboard\BaqatRequest;
use Modules\Baqat\Repositories\Dashboard\BaqatRepository as Baqat;
use Modules\Baqat\Traits\BaqatTrait;
use Modules\Baqat\Transformers\Dashboard\BaqatResource;
use Modules\Core\Traits\DataTable;
use Modules\User\Repositories\Dashboard\UserRepository as User;

class BaqatController extends Controller
{
    use BaqatTrait;

    protected $baqat;
    protected $user;

    public function __construct(Baqat $baqat, User $user)
    {
        $this->baqat = $baqat;
        $this->user = $user;
    }

    public function index()
    {
        return view('baqat::dashboard.baqat.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->baqat->QueryTable($request));
        $datatable['data'] = BaqatResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('baqat::dashboard.baqat.create');
    }

    public function store(BaqatRequest $request)
    {
        try {
            $create = $this->baqat->create($request);

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
        $baqa = $this->baqat->findById($id);
        if (!$baqa) {
            abort(404);
        }
        return view('baqat::dashboard.baqat.edit', compact('baqa'));
    }

    public function show($id)
    {
        $baqa = $this->baqat->findById($id);
        if (!$baqa) {
            abort(404);
        }
        return view('baqat::dashboard.baqat.show', compact('baqa'));
    }

    public function update(BaqatRequest $request, $id)
    {
        try {
            $update = $this->baqat->update($request, $id);

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
            $delete = $this->baqat->delete($id);

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

            $deleteSelected = $this->baqat->deleteSelected($request);
            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function calculateEndDate(Request $request)
    {
        try {
            $baqa = $this->baqat->findById($request->id);
            if (!$baqa) {
                return Response()->json([false, 'Package not found']);
            }

            $user = $this->user->findById($request->user_id);
            if (!$user) {
                return Response()->json([false, 'User not found']);
            }

            $startDate = Carbon::now()->format('Y-m-d');
            $lastUserSubscription = $this->getUserLastSubscription($request->user_id);
            if (!is_null($lastUserSubscription)) {
                $subscriptionStartDate = Carbon::createFromFormat('Y-m-d', $lastUserSubscription->start_at);
                $subscriptionEndDate = Carbon::createFromFormat('Y-m-d', $lastUserSubscription->end_at);
                $checkDate = Carbon::now()->between($subscriptionStartDate, $subscriptionEndDate);

                if ($subscriptionStartDate->gt(Carbon::now()) == true) {
                    return Response()->json([false, 'You have current active subscription', 'end_at' => '', 'start_at' => '']);
                }

                if ($checkDate == true) {
                    // there is active subscription
                    $endDate = $subscriptionEndDate->addDays(intval($baqa->duration_by_days))->format('Y-m-d');
                } else {
                    $endDate = Carbon::now()->addDays(intval($baqa->duration_by_days))->format('Y-m-d');
                }
            } else {
                $endDate = Carbon::now()->addDays(intval($baqa->duration_by_days))->format('Y-m-d');
            }

            return Response()->json([true, 'end_at' => $endDate, 'start_at' => $startDate]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
