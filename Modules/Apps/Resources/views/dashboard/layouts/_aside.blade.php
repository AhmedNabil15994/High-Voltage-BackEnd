<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item {{ active_menu(['home', '']) }}">
                <a href="{{ url(route('dashboard.home')) }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">{{ __('apps::dashboard.home.title') }}</span>
                    <span class="selected"></span>
                </a>
            </li>
        </ul>

        @if (\Auth::user()->can(['show_roles', 'show_users', 'show_admins']))
            <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                data-slide-speed="200">
                <li class="nav-item  {{ active_slide_menu(['roles', 'users', 'admins']) }}">

                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-users"></i>
                        <span class="title">{{ __('apps::dashboard.aside.tab.users') }}</span>
                        <span class="arrow open"></span>
                        <span class="selected"></span>
                    </a>

                    <ul class="sub-menu" style="display: block;">

                        @permission('show_roles')
                            <li class="nav-item {{ active_menu('roles') }}">
                                <a href="{{ url(route('dashboard.roles.index')) }}" class="nav-link nav-toggle">
                                    {{-- <i class="icon-briefcase"></i> --}}
                                    <span class="title">{{ __('apps::dashboard.aside.roles') }}</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        @endpermission

                        @permission('show_users')
                            <li class="nav-item {{ active_menu('users') }}">
                                <a href="{{ url(route('dashboard.users.index')) }}" class="nav-link nav-toggle">
                                    {{-- <i class="icon-briefcase"></i> --}}
                                    <span class="title">{{ __('apps::dashboard.aside.users') }}</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        @endpermission

                        @permission('show_admins')
                            <li class="nav-item {{ active_menu('admins') }}">
                                <a href="{{ url(route('dashboard.admins.index')) }}" class="nav-link nav-toggle">
                                    {{-- <i class="icon-briefcase"></i> --}}
                                    <span class="title">{{ __('apps::dashboard.aside.admins') }}</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        @endpermission
                    </ul>
                </li>
            </ul>
        @endif

        @if (Module::isEnabled('Order'))
            @if (\Auth::user()->can(['add_orders', 'show_orders', 'show_all_orders']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li
                        class="nav-item  {{ active_slide_menu(['create-order', 'current-orders', 'completed-orders', 'not-completed-orders', 'refunded-orders', 'all-orders']) }}">

                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.orders') }}</span>
                            <span class="arrow open"></span>
                            <span class="selected"></span>
                        </a>

                        <ul class="sub-menu" style="display: block;">

                            @permission('add_orders')
                                {{-- <li class="nav-item {{ active_menu('create-order') }}">
                                    <a href="{{ url(route('dashboard.orders.create')) }}" class="nav-link nav-toggle"> --}}
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        {{-- <span class="title">{{ __('apps::dashboard.aside.create_orders') }}</span>
                                    </a>
                                </li> --}}
                            @endpermission

                            @permission('show_orders')
                                <li class="nav-item {{ active_menu('new-orders') }}">
                                    <a href="{{ url(route('dashboard.new_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.new_orders') }}</span>
                                        @if (isset($ordersCount['new_orders']) && $ordersCount['new_orders'] > 0)
                                            <span class="badge badge-danger">{{ $ordersCount['new_orders'] }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item {{ active_menu('inprogress-orders') }}">
                                    <a href="{{ url(route('dashboard.inprogress_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.inprogress_orders') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ active_menu('ready-orders') }}">
                                    <a href="{{ url(route('dashboard.ready_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.ready_orders') }}</span>
                                    </a>
                                </li>


                                <li class="nav-item {{ active_menu('inway-orders') }}">
                                    <a href="{{ url(route('dashboard.inway_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.inway_orders') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ active_menu('delivered-orders') }}">
                                    <a href="{{ url(route('dashboard.delivered_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.delivered_orders') }}</span>
                                    </a>
                                </li>

                                {{-- <li class="nav-item {{ active_menu('current-orders') }}">
                                    <a href="{{ url(route('dashboard.current_orders.index')) }}"
                                        class="nav-link nav-toggle"> --}}
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        {{-- <span class="title">{{ __('apps::dashboard.aside.current_orders') }}</span>
                                        @if (isset($ordersCount['current_orders']) && $ordersCount['current_orders'] > 0)
                                            <span class="badge badge-danger">{{ $ordersCount['current_orders'] }}</span>
                                        @endif
                                    </a>
                                </li> --}}

                                {{-- <li class="nav-item {{ active_menu('completed-orders') }}">
                                    <a href="{{ url(route('dashboard.completed_orders.index')) }}"
                                        class="nav-link nav-toggle"> --}}
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        {{-- <span class="title">{{ __('apps::dashboard.aside.completed_orders') }}</span>
                                    </a>
                                </li> --}}

                                {{-- <li class="nav-item {{ active_menu('not-completed-orders') }}">
                                    <a href="{{ url(route('dashboard.not_completed_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.not_completed_orders') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ active_menu('refunded-orders') }}">
                                    <a href="{{ url(route('dashboard.refunded_orders.index')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.refunded_orders') }}</span>
                                    </a>
                                </li> --}}
                            @endpermission

                            @permission('show_all_orders')
                                <li class="nav-item {{ active_menu('all-orders') }}">
                                    <a href="{{ url(route('dashboard.all_orders.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.all_orders') }}</span>
                                    </a>
                                </li>
                            @endpermission

                        </ul>
                    </li>
                </ul>
            @endif
        @endif

        @if (Module::isEnabled('Catalog'))
            @if (\Auth::user()->can(['show_products', 'show_categories', 'show_options', 'show_addon_categories']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li
                        class="nav-item  {{ active_slide_menu(['products', 'categories', 'options', 'addon-categories']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.catalog') }}</span>
                            <span class="arrow open"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu" style="display: block;">

                            @permission('show_products')
                                <li class="nav-item {{ active_menu('products') }}">
                                    <a href="{{ url(route('dashboard.products.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.products') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_categories')
                                <li class="nav-item {{ active_menu('categories') }}">
                                    <a href="{{ url(route('dashboard.categories.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.categories') }}</span>
                                    </a>
                                </li>
                            @endpermission

                        </ul>
                    </li>
                </ul>
            @endif
        @endif

        @if (Module::isEnabled('Baqat'))
            @if (\Auth::user()->can(['show_baqat', 'show_baqat_subscriptions']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li
                        class="nav-item  {{ active_slide_menu(['baqat', 'baqat-subscriptions', 'current-subscriptions']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.baqat') }}</span>
                            <span class="arrow open"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu" style="display: block;">

                            @permission('show_baqat')
                                <li class="nav-item {{ active_menu('baqat') }}">
                                    <a href="{{ url(route('dashboard.baqat.index')) }}" class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.baqat') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_baqat_subscriptions')
                                <li class="nav-item {{ active_menu('current-subscriptions') }}">
                                    <a href="{{ url(route('dashboard.baqat_subscriptions.current_index')) }}"
                                        class="nav-link nav-toggle">
                                        <span
                                            class="title">{{ __('apps::dashboard.aside.current_baqat_subscriptions') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_baqat_subscriptions')
                                <li class="nav-item {{ active_menu('baqat-subscriptions') }}">
                                    <a href="{{ url(route('dashboard.baqat_subscriptions.index')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.baqat_subscriptions') }}</span>
                                    </a>
                                </li>
                            @endpermission

                        </ul>
                    </li>
                </ul>
            @endif
        @endif

        @if (Module::isEnabled('Company'))
            @if (\Auth::user()->can(['show_companies', 'show_delivery_charges', 'show_drivers', 'show_working_times']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li
                        class="nav-item  {{ active_slide_menu(['companies', 'delivery-charges', 'drivers', 'working-times']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-truck"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.companies') }}</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">

                            @permission('show_companies')
                                <li class="nav-item {{ active_menu('companies') }}">
                                    <a href="{{ url(route('dashboard.companies.index')) }}" class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.companies') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_delivery_charges')
                                <li class="nav-item {{ active_menu('delivery-charges') }}">
                                    <a href="{{ url(route('dashboard.delivery-charges.index')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.delivery_charges') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_drivers')
                                <li class="nav-item {{ active_menu('drivers') }}">
                                    <a href="{{ url(route('dashboard.drivers.index')) }}" class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.drivers') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_working_times')
                                <li class="nav-item {{ active_menu('working-times') }}">
                                    <a href="{{ url(route('dashboard.working_times.index')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.working_times') }}</span>
                                    </a>
                                </li>
                            @endpermission

                        </ul>
                    </li>
                </ul>
            @endif
        @endif

        @if (Module::isEnabled('Report'))
            @if (\Auth::user()->can(['show_reports']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li class="nav-item  {{ active_slide_menu(['reports']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-folder-open"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.reports') }}</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">

                            @permission('show_reports')
                                <li class="nav-item {{ active_menu('reports') }}">
                                    <a href="{{ url(route('dashboard.reports.get_order_reports')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.all_reports') }}</span>
                                    </a>
                                </li>
                            @endpermission

                        </ul>
                    </li>
                </ul>
            @endif
        @endif

        {{-- @if (Module::isEnabled('Report'))
            @if (\Auth::user()->can(['show_product_sale_reports', 'show_order_sale_reports', 'show_vendors_reports', 'show_product_stock_reports']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li
                        class="nav-item  {{ active_slide_menu(['product-sales-reports', 'product-stock-reports', 'vendors-reports', 'order-sales-reports']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-folder-open"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.reports') }}</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">

                            @permission('show_product_sale_reports')
                                <li class="nav-item {{ active_menu('product-sales-reports') }}">
                                    <a href="{{ url(route('dashboard.reports.product_sale')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.product_sales') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_product_stock_reports')
                                <li class="nav-item {{ active_menu('product-stock-reports') }}">
                                    <a href="{{ url(route('dashboard.reports.product_stock')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.product_stock') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_vendors_reports')
                                <li class="nav-item {{ active_menu('vendors-reports') }}">
                                    <a href="{{ url(route('dashboard.reports.vendors')) }}" class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.vendors_reports') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_order_sale_reports')
                                <li class="nav-item {{ active_menu('order-sales-reports') }}">
                                    <a href="{{ url(route('dashboard.reports.order_sale')) }}"
                                        class="nav-link nav-toggle">
                                        <span class="title">{{ __('apps::dashboard.aside.order_sales') }}</span>
                                    </a>
                                </li>
                            @endpermission

                        </ul>
                    </li>
                </ul>
            @endif
        @endif --}}

        @if (Module::isEnabled('Area'))
            @if (\Auth::user()->can(['show_countries', 'show_cities', 'show_states']))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li class="nav-item  {{ active_slide_menu(['countries', 'cities', 'states']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-globe"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.countries') }}</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">

                            @permission('show_countries')
                                <li class="nav-item {{ active_menu('countries') }}">
                                    <a href="{{ url(route('dashboard.countries.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.countries') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_cities')
                                <li class="nav-item {{ active_menu('cities') }}">
                                    <a href="{{ url(route('dashboard.cities.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.cities') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_states')
                                <li class="nav-item {{ active_menu('states') }}">
                                    <a href="{{ url(route('dashboard.states.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.states') }}</span>
                                    </a>
                                </li>
                            @endpermission
                        </ul>
                    </li>
                </ul>
            @endif
        @endif

        @if (\Auth::user()->can(['show_slider', 'show_coupon', 'show_notifications', 'show_advertising']))
            <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                data-slide-speed="200">
                <li
                    class="nav-item  {{ active_slide_menu(['slider', 'coupons', 'notifications', 'advertising-groups', 'advertising']) }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-gift"></i>
                        <span class="title">{{ __('apps::dashboard.aside.tab.marketing') }}</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">

                        @permission('show_slider')
                            <li class="nav-item {{ active_menu('slider') }}">
                                <a href="{{ url(route('dashboard.slider.index')) }}" class="nav-link nav-toggle">
                                    <span class="title">{{ __('apps::dashboard.aside.slider') }}</span>
                                </a>
                            </li>
                        @endpermission

                        @permission('show_advertising')
{{--                            <li class="nav-item {{ active_menu('advertising-groups') }}">--}}
{{--                                <a href="{{ url(route('dashboard.advertising_groups.index')) }}"--}}
{{--                                    class="nav-link nav-toggle">--}}
{{--                                    --}}{{-- <i class="icon-briefcase"></i> --}}
{{--                                    <span class="title">{{ __('apps::dashboard.aside.advertising_groups') }}</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        @endpermission

                        @permission('show_coupon')
                            <li class="nav-item {{ active_menu('coupons') }}">
                                <a href="{{ url(route('dashboard.coupons.index')) }}" class="nav-link nav-toggle">
                                    {{-- <i class="icon-calculator"></i> --}}
                                    <span class="title">{{ __('apps::dashboard.aside.coupons') }}</span>
                                </a>
                            </li>
                        @endpermission

                        @permission('show_notifications')
{{--                            <li class="nav-item {{ active_menu('notifications') }}">--}}
{{--                                <a href="{{ url(route('dashboard.notifications.index')) }}" class="nav-link nav-toggle">--}}
{{--                                    --}}{{-- <i class="icon-briefcase"></i> --}}
{{--                                    <span class="title">{{ __('apps::dashboard.aside.notifications') }}</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        @endpermission
                    </ul>
                </li>
            </ul>
        @endif

        @if (\Auth::user()->can(['show_pages']) || \Auth::user()->tocaan_perm == 1)
            <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                data-slide-speed="200">
                <li class="nav-item  {{ active_slide_menu(['pages']) }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.tab.setting') }}</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">

                        {{-- @if (\Auth::user()->tocaan_perm == 1)
                            <li class="nav-item {{ active_menu('themes') }}">
                                <a href="{{ url(route('developer.themes.colors.index')) }}"
                                    class="nav-link nav-toggle">
                                    <span class="title">{{ __('apps::dashboard.aside.theme_colors') }}</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        @endif --}}

                        @permission('show_pages')
                            <li class="nav-item {{ active_menu('pages') }}">
                                <a href="{{ url(route('dashboard.pages.index')) }}" class="nav-link nav-toggle">
                                    <span class="title">{{ __('apps::dashboard.aside.pages') }}</span>
                                </a>
                            </li>
                        @endpermission

                    </ul>
                </li>
            </ul>
        @endif

    </div>
</div>
