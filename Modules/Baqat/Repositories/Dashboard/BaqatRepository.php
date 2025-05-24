<?php

namespace Modules\Baqat\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Modules\Baqat\Entities\Baqat;

class BaqatRepository
{
    protected $baqat;

    public function __construct(Baqat $baqat)
    {
        $this->baqat = $baqat;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        return $this->baqat->orderBy($order, $sort)->get();
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->baqat->active()->orderBy($order, $sort)->get();
    }

    public function findById($id, $with = [])
    {
        $query = $this->baqat->withDeleted();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->find($id);
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $baqat = $this->baqat->create([
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "description" => $request->description,
                "duration_description" => $request->duration_description,
                "duration_by_days" => $request->duration_by_days,
                "price" => $request->price,
                "client_price" => $request->client_price,
                "sort" => $request->sort,
            ]);

            $this->baqaOffer($baqat, $request);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $baqat = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($baqat) : null;
        try {

            $baqat->update([
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "description" => $request->description,
                "duration_description" => $request->duration_description,
                "duration_by_days" => $request->duration_by_days,
                "price" => $request->price,
                "client_price" => $request->client_price,
                "sort" => $request->sort,
            ]);

            $this->baqaOffer($baqat, $request);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($baqat)
    {
        $baqat->restore();
        return true;
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $baqat = $this->findById($id);
            if ($baqat) {
                if ($baqat->trashed()):
                    $baqat->forceDelete();
                else:
                    $baqat->delete();
                endif;
            } else {
                return false;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->baqat->with([])->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
            });
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // Search Countries by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only') {
            $query->onlyDeleted();
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with') {
            $query->withDeleted();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '1') {
            $query->active();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '0') {
            $query->unactive();
        }

        return $query;
    }

    public function baqaOffer($model, $request)
    {
        if (isset($request['offer_status']) && $request['offer_status'] == 'on') {
            $data = [
                'status' => ($request['offer_status'] == 'on') ? true : false,
                'start_at' => $request['start_at'] ? $request['start_at'] : $model->offer->start_at,
                'end_at' => $request['end_at'] ? $request['end_at'] : $model->offer->end_at,
            ];

            if ($request['offer_type'] == 'amount' && !is_null($request['offer_price'])) {
                $data['offer_price'] = $request['offer_price'];
                $data['percentage'] = null;
            } elseif ($request['offer_type'] == 'percentage' && !is_null($request['offer_percentage'])) {
                $data['offer_price'] = null;
                $data['percentage'] = $request['offer_percentage'];
            } else {
                $data['offer_price'] = null;
                $data['percentage'] = null;
            }

            $model->offer()->updateOrCreate(['baqat_id' => $model->id], $data);
        } else {
            if ($model->offer) {
                $model->offer()->delete();
            }
        }
    }
}
