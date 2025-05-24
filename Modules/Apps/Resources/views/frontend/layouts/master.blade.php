<!DOCTYPE html>
<html>

@include('apps::frontend.layouts._header')

<body>
    @include('apps::frontend.layouts.header-section', compact('aboutUs'))
    @yield('content')
    @include('apps::frontend.layouts.footer-section')
    @include('apps::frontend.layouts.scripts')
</body>

</html>
