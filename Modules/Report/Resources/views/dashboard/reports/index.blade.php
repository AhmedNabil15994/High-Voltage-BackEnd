@extends('apps::dashboard.layouts.app')
@section('title', __('report::dashboard.reports.index.title'))
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('report::dashboard.reports.index.title') }}</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">

                        <div class="portlet-body">

                            <div class="row widget-row">

                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.late_orders.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.reports.late_orders.index') }}">{{ __('report::dashboard.reports.index.form.late_orders.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.subscriptions_status.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.reports.subscriptions_status.index') }}">{{ __('report::dashboard.reports.index.form.subscriptions_status.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.delivered_orders.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.reports.delivered_orders.index') }}">{{ __('report::dashboard.reports.index.form.delivered_orders.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.order_coupons.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.coupons.index') }}">{{ __('report::dashboard.reports.index.form.order_coupons.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.delivered_received_orders.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.drivers.index') }}">{{ __('report::dashboard.reports.index.form.delivered_received_orders.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.orders_states.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.states.index') }}">{{ __('report::dashboard.reports.index.form.orders_states.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-3">
                                    <div
                                        class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">
                                            {{ __('report::dashboard.reports.index.form.user_orders.title') }}</h4>
                                        <div class="widget-thumb-wrap">
                                            {{-- <i class="widget-thumb-icon bg-green icon-bulb"></i> --}}
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">
                                                    <a
                                                        href="{{ route('dashboard.users.index') }}">{{ __('report::dashboard.reports.index.form.user_orders.description') }}</a>
                                                </span>
                                                {{-- <span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">7,644</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        jQuery(document).ready(function() {

        });
    </script>
@stop
