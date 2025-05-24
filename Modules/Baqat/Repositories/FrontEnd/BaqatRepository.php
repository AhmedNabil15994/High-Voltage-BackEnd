<?php

namespace Modules\Baqat\Repositories\FrontEnd;

use Modules\Baqat\Entities\Baqat;

class BaqatRepository
{
    protected $baqat;

    public function __construct(Baqat $baqat)
    {
        $this->baqat = $baqat;
    }

    public function getAllActive($order = 'sort', $sort = 'desc')
    {
        return $this->baqat->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
        ])->active()->orderBy($order, $sort)->get();
    }

    public function findById($id, $with = [])
    {
        $query = $this->baqat->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
        ]);
        
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->find($id);
    }
}
