<?php

namespace Modules\Catalog\Repositories\Dashboard;

use Modules\Catalog\Entities\CustomAddon;

class CustomAddonRepository
{
    protected $customAddon;

    public function __construct(CustomAddon $customAddon)
    {
        $this->customAddon = $customAddon;
    }

    public function getAll($order = 'sort', $sort = 'asc')
    {
        return $this->customAddon->orderBy($order, $sort)->get();
    }

    public function getAllActive($order = 'sort', $sort = 'asc')
    {
        return $this->customAddon->active()->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        $category = $this->customAddon->withDeleted()->find($id);
        return $category;
    }

    public function restoreSoftDelete($model)
    {
        $model->restore();
        return true;
    }
}
