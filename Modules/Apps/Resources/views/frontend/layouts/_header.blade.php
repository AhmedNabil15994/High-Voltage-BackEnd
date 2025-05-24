<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="author" content="{{ config('setting.app_name.' . locale()) ?? '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>@yield('title', 'High Voltage Laundry') || {{ config('setting.app_name.' . locale()) }}</title>

    <!--====== Favicon ======-->
    <link rel="shortcut icon"
        href="{{ config('setting.images.favicon') ? asset(config('setting.images.favicon')) : asset('frontend/assets/images/logo/favicon.png') }}"
        type="images/x-icon" />

    <!--====== CSS Here ======-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">

    @stack('styles')

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lightcase.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/default.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sweetalert2.min.css') }}">

    @if (locale() == 'ar')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style-ar.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive-ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style-en.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive-en.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/working-times.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/nav-mobile-ar.css') }}">

    <style>
        body {
            font-family: {{ locale() == 'ar' ? "'Noto Kufi Arabic', sans-serif" : 'arial' }};
        }

        /* start loader style */

        #loaderDiv,
        #headerLoaderDiv {
            display: none;
            margin: 15px auto;
            justify-content: center;
        }

        .generalLoaderDiv {
            display: none;
            margin: 15px 100px;
            justify-content: center;
        }

        #loaderCouponDiv {
            display: none;
            margin: 15px 100px;
            justify-content: center;
        }

        #loaderDiv .my-loader,
        #headerLoaderDiv .my-loader,
        .generalLoaderDiv .my-loader,
        #loaderCouponDiv .my-loader {
            border: 10px solid #f3f3f3;
            border-radius: 50%;
            border-top: 10px solid #3498db;
            width: 70px;
            height: 70px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* end loader style */

        .empty-cart-title {
            text-align: center;
        }

        .label-tag-product {
            position: relative;
            color: #fff;
            margin: 10px;
            border-radius: 20px;
            padding: 2px 15px;
            font-size: 12px;
        }

        .percentage-discount {
            color: red;
        }

        figure img {
            max-width: 100%;
        }

        @media (max-width: 991px) {
            .checkout-form-list .form-select {
                overflow: auto;
            }
        }
    </style>

    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            a[href]:after {
                content: none !important;
            }

            .contentPrint {
                width: 100%;
                /* font-family: tahoma; */
                font-size: 16px;
            }

            .invoice-body td.notbold {
                padding: 2px;
            }

            h2.invoice-title.uppercase {
                margin-top: 0px;
            }

            .invoice-content-2 {
                background-color: #fff;
                padding: 5px 20px;
            }

            .invoice-content-2 .invoice-cust-add,
            .invoice-content-2 .invoice-head {
                margin-bottom: 0px;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }
        }
    </style>

    {{-- Start - Bind Css Code From Dashboard Daynamic --}}
    {!! config('setting.custom_codes.css_in_head') ?? null !!}
    {{-- End - Bind Css Code From Dashboard Daynamic --}}

    @yield('externalStyle')

    {{-- Start - Bind Js Code From Dashboard Daynamic --}}
    {!! config('setting.custom_codes.js_before_head') ?? null !!}
    {{-- End - Bind Js Code From Dashboard Daynamic --}}

</head>
