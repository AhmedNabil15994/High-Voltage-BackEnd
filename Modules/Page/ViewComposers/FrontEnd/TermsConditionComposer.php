<?php

namespace Modules\Page\ViewComposers\FrontEnd;

use Illuminate\View\View;
use Modules\Page\Repositories\FrontEnd\PageRepository as Page;

class TermsConditionComposer
{
    public $termsAndCondition;

    public function __construct(Page $page)
    {
        $this->termsAndCondition = $page->getTermsAndConditionPage();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['termsAndCondition' => $this->termsAndCondition]);
    }
}
