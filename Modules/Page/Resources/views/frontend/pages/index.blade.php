@extends('apps::frontend.layouts.master')
@section('title', $page->title)
@section('content')

    <!-- breadcrumb area start -->
    <section class="breadcrumb-area pt-40 pb-40 bg_img" data-overlay="7"
        data-background="{{ asset('frontend/assets/images/bg/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-text">
                        <h1 class="breadcrumb-title">{{ __('About Us') }}</h1>
                        <p>{{ __('Welcome to') }} {{ config('setting.app_name.' . locale()) }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('About Us') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- about area start -->
    <section class="about-area about-area-2 about-area-4 pt-50 pb-140">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="about-left-side about-left-side-4">
                        <div class="about-thumb-big" data-tilt data-tilt-perspective="3000">
                            <img src="{{ asset('frontend/assets/images/about/about-4-thumb-1.jpg') }}"
                                alt="{{ $page->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 my-auto">
                    <div class="about-right-side about-right-side-2 about-right-side-4">
                        <div class="section-heading section-heading-2 mb-45">
                            <h5 class="sub-title mb-22">{{ $page->title }}</h5>
                            <h2 class="title">{{ config('setting.app_name.' . locale()) }}</h2>
                        </div>
                        <div class="about-list-wrapper">
                            <div class="about-list-author mt-40">
                                {!! $page->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about area end -->

@endsection

@section('externalJs')

    <script></script>

@endsection
