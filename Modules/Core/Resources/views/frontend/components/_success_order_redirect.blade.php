<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- BASE CSS -->
    <link href="{{ asset('frontend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/wizard.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/vendors.css') }}" rel="stylesheet">

    <script type="text/javascript">
        function delayedRedirect() {
            window.location = "{{ route('frontend.home') }}"
        }
    </script>
</head>

<body onLoad="setTimeout('delayedRedirect()', 5000)" style="background-color:#fff;">
    <div id="success">
        <div class="icon icon--order-success svg">
            <svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
                <g fill="none" stroke="#8EC343" stroke-width="2">
                    <circle cx="36" cy="36" r="35"
                        style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                    <path d="M17.417,37.778l9.93,9.909l25.444-25.393"
                        style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                </g>
            </svg>
        </div>
        <h4><span>{{ __('Thank you for your time') }}</span>{{ $successMessage }}</h4>
        <small>{{ __('You will be redirected again in 5 seconds.') }}</small>
    </div><!-- END SEND MAIL SCRIPT -->

</body>

</html>
