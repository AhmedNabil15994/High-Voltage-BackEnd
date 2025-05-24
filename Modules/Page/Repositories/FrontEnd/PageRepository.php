<?php

namespace Modules\Page\Repositories\FrontEnd;

use Modules\Page\Entities\Page;

class PageRepository
{
    protected $page;
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $pages = $this->page->active()->orderBy($order, $sort)->get();
        return $pages;
    }

    public function findBySlug($slug)
    {
        $page = $this->page->anyTranslation('slug', $slug)->first();
        return $page;
    }

    public function findById($id)
    {
        $page = $this->page->find($id);
        return $page;
    }

    public function getAboutUsPage()
    {
        $id = config('setting.other.about_us') ?? null;
        return $this->page->find($id);
    }

    public function getTermsAndConditionPage()
    {
        $id = config('setting.other.terms') ?? null;
        return $this->page->find($id);
    }

    public function checkRouteLocale($model, $slug)
    {
        // if ($model->translate()->where('slug', $slug)->first()->locale != locale()) {
        //     return false;
        // }
        if ($array = $model->getTranslations("slug")) {
            $locale = array_search($slug, $array);

            return $locale == locale();
        }

        return true;
    }
}
