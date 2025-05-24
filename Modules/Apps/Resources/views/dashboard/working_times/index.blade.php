@extends('apps::dashboard.layouts.app')
@section('title', __('apps::dashboard.working_days.index.title'))
@section('css')

    <style>
        .is_full_day {
            margin-left: 15px;
            margin-right: 15px;
        }

        .collapse-custom-time {
            display: none;
        }

        .times-row {
            margin-bottom: 5px;
        }
    </style>

@endsection
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
                        <a href="#">{{ __('apps::dashboard.working_days.index.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" role="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.working_times.store') }}">
                    @csrf
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">

                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#pickupTimes" data-toggle="tab">
                                                        {{ __('apps::dashboard.working_days.index.tabs.pickup_times') }}
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#deliveryTimes" data-toggle="tab">
                                                        {{ __('apps::dashboard.working_days.index.tabs.delivery_times') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">

                                <div class="tab-pane active fade in" id="pickupTimes">
                                    @include('apps::dashboard.working_times.form', [
                                        'requestType' => 'pickup', 'workingDays' => $pickupWorkingDays,
                                    ])
                                </div>

                                <div class="tab-pane fade in" id="deliveryTimes">
                                    @include('apps::dashboard.working_times.form', [
                                        'requestType' => 'delivery', 'workingDays' => $deliveryWorkingDays,
                                    ])
                                </div>

                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{ __('apps::dashboard.general.edit_btn') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @include('apps::dashboard.working_times.times_script')
@stop
