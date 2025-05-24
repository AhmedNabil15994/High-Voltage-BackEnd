<!-- footer area start -->
<footer class="site-footer bg-4 site-footer-2">

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="copyright text-center">
                    <p>© {{ date('Y') }} {{ __('All Rights Reserved') }}
                        {{ config('setting.app_name.' . locale()) }}
                        ..
                        {{ __('Designed and Developed by') }} <a
                            href="https://www.tocaan.com/">{{ __('Tocaan Company') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-nav">
        <ul class="nav nav-fill">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('frontend.home') }}">
                    <i class="fal fa-home"></i>
                    <span>{{ __('Home') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('frontend.orders.index') }}">
                    <i class="fal fa-shopping-basket"></i>
                    <span>{{ __('Orders') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('frontend.baqat.index') }}">
                    <i class="fal fa-store"></i>
                    <span>{{ __('Subscriptions') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('frontend.profile.index') }}">
                    <i class="fal fa-user"></i>
                    <span>{{ __('Account') }}</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('frontend.more_items') }}">
                    <i class="fal fa-bars"></i>
                    <span>{{ __('More') }}</span>
                </a>
            </li>
        </ul>
    </div>

</footer>
<!-- footer area end -->
