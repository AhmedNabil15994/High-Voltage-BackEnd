<?php

namespace Modules\User\Repositories\FrontEnd;

use Illuminate\Support\Facades\DB;
use Modules\User\Entities\Address;

class AddressRepository
{
    protected $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getAllByUsrId()
    {
        $addresses = $this->address->where('user_id', auth()->id())->with(['state' => function ($q) {
            $q->with(['city' => function ($q) {
                $q->with('country');
            }]);
        }])->orderBy('id', 'DESC')->get();
        return $addresses;
    }

    public function getAddressesCountByUsrId()
    {
        return $this->address->where('user_id', auth()->id())->count();
    }

    public function findById($id)
    {
        $address = $this->address->where('user_id', auth()->id())->with('state')->find($id);
        return $address;
    }

    public function findByIdWithoutAuth($id, $userId)
    {
        return $this->address->with('state')->find($id);
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $userAddressesCount = $this->getAddressesCountByUsrId();

            $this->address->create([
                'email' => $request['email'] ?? auth()->user()->email,
                'username' => $request['username'] ?? auth()->user()->name,
                'mobile' => $request['mobile'] ?? auth()->user()->mobile,
                'address' => $request['address'] ?? null,
                'block' => $request['block'] ?? null,
                'street' => $request['street'] ?? null,
                'building' => $request['building'] ?? null,
                'state_id' => $request['state_id'] ?? null,
                //'civil_id' => $request['civil_id'] ?? null,
                'user_id' => auth()->id(),
                'avenue' => $request['avenue'] ?? null,
                'floor' => $request['floor'] ?? null,
                'flat' => $request['flat'] ?? null,
                'automated_number' => $request['automated_number'] ?? null,
                'is_default' => $userAddressesCount == 0,
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
            ]);

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

        $address = $this->findById($id);

        try {

            $address->update([
                'email' => $request['email'] ?? auth()->user()->email,
                'username' => $request['username'] ?? auth()->user()->name,
                'mobile' => $request['mobile'] ?? auth()->user()->mobile,
                'address' => $request['address'] ?? null,
                'block' => $request['block'] ?? null,
                'street' => $request['street'] ?? null,
                'building' => $request['building'] ?? null,
                'state_id' => $request['state_id'] ?? null,
                'avenue' => $request['avenue'] ?? null,
                'floor' => $request['floor'] ?? null,
                'flat' => $request['flat'] ?? null,
                'automated_number' => $request['automated_number'] ?? null,
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            $model->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function makeDefaultAddress($request, $id)
    {
        DB::beginTransaction();

        try {

            $this->address->where('user_id', auth()->id())->update(['is_default' => 0]);
            $this->address->where('user_id', auth()->id())->where('id', $id)->update(['is_default' => 1]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
