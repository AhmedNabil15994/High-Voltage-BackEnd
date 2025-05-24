<?php

namespace Modules\Page\ViewComposers\FrontEnd;

use Illuminate\View\View;
use Modules\Page\Repositories\FrontEnd\PageRepository as Page;

class PageComposer
{
    public $aboutUs;

    public function __construct(Page $page)
    {
        $this->aboutUs = $page->getAboutUsPage();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['aboutUs' => $this->aboutUs]);
    }
}
