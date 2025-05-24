<!--========= JS Here =========-->
<script src="{{ asset('frontend/assets/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>

@stack('scripts')

<script src="{{ asset('frontend/assets/js/counterup.min.js') }}"></script>

<script src="{{ asset('frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.appear.js') }}"></script>

<script src="{{ asset('frontend/assets/js/jquery.meanmenu.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.nice-select.min.js') }}"></script>


<script src="{{ asset('frontend/assets/js/lightcase.js') }}"></script>
<script src="{{ asset('frontend/assets/js/odometer.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>

<script src="{{ asset('frontend/assets/js/waypoint.js') }}"></script>
<script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
{{-- <script src="{{ asset('frontend/assets/js/tilt.jquery.min.js') }}"></script> --}}
<script src="{{ asset('frontend/assets/js/jquery.scrollUp.min.js') }}"></script>

<script src="{{ asset('frontend/assets/js/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('frontend/assets/js/main.js') }}"></script>

<script>
    const currentLoction = location.href;
    const menuItem = document.querySelectorAll('.main-menu ul a');
    const menuLength = menuItem.length
    for (let i = 0; i < menuLength; i++) {
        if (menuItem[i].href === currentLoction) {
            menuItem[i].className = "active"
        }
    }
</script>

@include('apps::frontend.layouts._js')

{{-- Start - Bind Js Code From Dashboard Daynamic --}}
{!! config('setting.custom_codes.js_before_body') ?? null !!}
{{-- End - Bind Js Code From Dashboard Daynamic --}}

@yield('externalJs')
