@extends('apps::frontend.layouts.master')
@section('title', __('More Items'))
@section('externalStyle')
    <style>
    </style>
@endsection
@section('content')

    <div class="blog-area pt-40 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-12">
                    <a href="{{ $aboutUs ? route('frontend.pages.index', $aboutUs->slug) : '#' }}" class="sidebar-wrap more">
                        <div class="widget mb-20">
                            <div class="widget-title-box">
                                <h3 class="widget-title">
                                    {{ __('About Us') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <a href="{{ route('frontend.offers.index') }}" class="sidebar-wrap more">
                        <div class="widget mb-20">
                            <div class="widget-title-box">
                                <h3 class="widget-title">
                                    {{ __('Offers') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <a href="{{ route('frontend.contact_us') }}" class="sidebar-wrap more">
                        <div class="widget mb-20">
                            <div class="widget-title-box">
                                <h3 class="widget-title">
                                    {{ __('Contact Us') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <a href="{{ route('frontend.profile.edit') }}" class="sidebar-wrap more">
                        <div class="widget mb-20">
                            <div class="widget-title-box">
                                <h3 class="widget-title">
                                    {{ __('Settings') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-12">

                    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
                        @if ($localeCode != locale())
                            <a hreflang="{{ $localeCode }}"
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                class="sidebar-wrap more">
                                <div class="widget mb-20">
                                    <div class="widget-title-box">
                                        <h3 class="widget-title">{{ __('Language') }}</h3>
                                        <span>{{ $properties['native'] }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach

                </div>

                @if (auth()->check())
                    <div class="col-xl-4 col-lg-12">

                        <a onclick="event.preventDefault();document.getElementById('logout').submit();" href="javascript:;"
                            class="sidebar-wrap more">
                            <div class="widget mb-20">
                                <div class="widget-title-box">
                                    <h3 class="widget-title">
                                        {{ __('Log Out') }}
                                    </h3>
                                </div>
                            </div>
                        </a>

                        <form id="logout" action="{{ route('frontend.logout') }}" method="POST">
                            @csrf
                        </form>

                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {

        });
    </script>

@endsection
