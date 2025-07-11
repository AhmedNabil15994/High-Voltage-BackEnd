<?php

namespace Modules\User\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Area\Entities\State;
use Modules\Core\Traits\CoreTrait;
use Modules\User\Entities\User;

class DriverRepository
{
    use CoreTrait;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /*
     * Get All Normal Users with Driver Roles
     */
    public function getAllDrivers($order = 'id', $sort = 'desc')
    {
        $users = $this->user->whereHas('roles.perms', function ($query) {
            $query->where('name', 'driver_access');
        })->orderBy($order, $sort)->get();
        return $users;
    }

    /*
     * Find Object By ID
     */
    public function findById($id)
    {
        $user = $this->user->withDeleted()->with('company')->find($id);
        return $user;
    }

    /*
     * Find Object By ID
     */
    public function findByEmail($email)
    {
        $user = $this->user->where('email', $email)->first();
        return $user;
    }

    /*
     * Create New Object & Insert to DB
     */
    public function create($request)
    {
        DB::beginTransaction();

        try {

            // $image = $request['image'] ? path_without_domain($request['image']) : '/uploads/users/user.png';

            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => Hash::make($request['password']),
                // 'image' => $image,
                'company_id' => $request['company_id'] ?? 1,
                'is_verified' => $request->is_verified == 'on' ? 1 : 0,
                "code_verified" => null,
                "setting" => [
                    "lang" => locale(),
                ],
                'country_id' => $request['country_id'] ?? 1,
                'calling_code' => $request['calling_code'] ?? '965',
                'maximum_received_orders_count' => $request->maximum_received_orders_count,
                'maximum_delivery_orders_count' => $request->maximum_delivery_orders_count,
            ];

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage(public_path(config('core.config.user_img_path')), $request->image);
                $data['image'] = config('core.config.user_img_path') . '/' . $imgName;
            } else {
                $data['image'] = null;
            }

            $user = $this->user->create($data);

            if ($request['roles'] != null) {
                $this->saveRoles($user, $request);
            }

            if (!empty($request->statuses)) {
                $states = State::active()->whereIn('id', array_keys($request->statuses))->get();
                $user->driverStates()->attach($states);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function saveRoles($user, $request)
    {
        foreach ($request['roles'] as $key => $value) {
            $user->attachRole($value);
        }

        return true;
    }

    /*
     * Find Object By ID & Update to DB
     */
    public function update($request, $id)
    {
        DB::beginTransaction();

        $user = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelte($user) : null;

        try {

            // $image = $request['image'] ? path_without_domain($request['image']) : $user->image;

            if ($request['password'] == null) {
                $password = $user['password'];
            } else {
                $password = Hash::make($request['password']);
            }

            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => $password,
                // 'image' => $image,
                'company_id' => $request['company_id'] ?? 1,
                'is_verified' => $request->is_verified == 'on' ? 1 : 0,
                // "code_verified" => generateRandomNumericCode(),
                "setting" => [
                    "lang" => locale(),
                ],
                'country_id' => $request['country_id'] ?? 1,
                'calling_code' => $request['calling_code'] ?? '965',
                'maximum_received_orders_count' => $request->maximum_received_orders_count,
                'maximum_delivery_orders_count' => $request->maximum_delivery_orders_count,
            ];

            if ($request->image) {
                if (!empty($user->image) && !in_array($user->image, config('core.config.special_images'))) {
                    File::delete($user->image); ### Delete old image
                }
                $imgName = $this->uploadImage(public_path(config('core.config.user_img_path')), $request->image);
                $data['image'] = config('core.config.user_img_path') . '/' . $imgName;
            } else {
                $data['image'] = $user->image;
            }

            $user->update($data);

            if ($request['roles'] != null) {
                DB::table('role_user')->where('user_id', $id)->delete();

                foreach ($request['roles'] as $key => $value) {
                    $user->attachRole($value);
                }
            }

            if (!empty($request->statuses)) {
                $states = State::active()->whereIn('id', array_keys($request->statuses))->get();
                $user->driverStates()->sync($states);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model) {
                if ($model && !empty($model->image) && !in_array($model->image, config('core.config.special_images'))) {
                    File::delete($model->image); ### Delete old image
                }
                if ($model->trashed()):
                    $model->forceDelete();
                else:
                    $model->delete();
                endif;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /*
     * Find all Objects By IDs & Delete it from DB
     */
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

    /*
     * Generate Datatable
     */
    public function QueryTable($request)
    {
        $query = $this->user->with('company')
            ->withCount('driverOrders')
            ->withCount('driverDeliveredOrderStatuses')
            ->where('id', '!=', auth()->id())->whereHas('roles.perms', function ($query) {
            $query->where('name', 'driver_access');
        })->where(function ($query) use ($request) {

            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere('name', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere('email', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere('mobile', 'like', '%' . $request->input('search.value') . '%');

            $query->orWhereHas('company', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('search.value') . '%');
            });
        });

        $query = $this->filterDataTable($query, $request);
        return $query;
    }

    /*
     * Filteration for Datatable
     */
    public function filterDataTable($query, $request)
    {
        // Search Users by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['roles']) && $request['req']['roles'] != '') {

            $query->whereHas('roles', function ($query) use ($request) {
                $query->where('id', $request['req']['roles']);
            });
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only') {
            $query->onlyDeleted();
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with') {
            $query->withDeleted();
        }

        return $query;
    }
}
