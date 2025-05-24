<?php

view()->composer(['setting::dashboard.index'], \Modules\Page\ViewComposers\Dashboard\PageComposer::class);
view()->composer(['apps::frontend.layouts.master', 'apps::frontend.layouts._more-items'], \Modules\Page\ViewComposers\FrontEnd\PageComposer::class);
view()->composer(['order::frontend.orders.start-request'], \Modules\Page\ViewComposers\FrontEnd\TermsConditionComposer::class);
