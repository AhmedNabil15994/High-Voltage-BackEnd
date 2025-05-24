@extends('apps::dashboard.layouts.app')
@section('title', __('baqat::dashboard.baqat_subscriptions.routes.create'))
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
                        <a href="{{ url(route('dashboard.baqat_subscriptions.index')) }}">
                            {{ __('baqat::dashboard.baqat_subscriptions.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('baqat::dashboard.baqat_subscriptions.routes.create') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.baqat_subscriptions.store') }}">
                    @csrf
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    {{-- <div class="panel-heading">
                                        <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                    </div> --}}
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#general" data-toggle="tab">
                                                        {{ __('baqat::dashboard.baqat_subscriptions.form.tabs.general') }}
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

                                <div class="tab-pane active fade in" id="global_setting">
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat_subscriptions.form.user_id') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="user_id" id="userSelect2"
                                                    class="form-control select2-allow-clear" data-name="user_id">
                                                    <option value="">
                                                        {{ __('baqat::dashboard.baqat_subscriptions.form.select_user') }}
                                                    </option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->id . ' - ' . $user->name . ' - ' . $user->mobile }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat_subscriptions.form.baqat_id') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="baqat_id" id="baqaSelect2"
                                                    class="form-control select2-allow-clear" data-name="baqat_id">
                                                    <option value="">
                                                        {{ __('baqat::dashboard.baqat_subscriptions.form.select_baqa') }}
                                                    </option>
                                                    @foreach ($activeBaqat as $baqa)
                                                        <option value="{{ $baqa->id }}">
                                                            {{ $baqa->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{-- <div class="form-group" id="startDateSection">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat_subscriptions.form.start_at') }}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group input-medium date date-picker"
                                                    style="width: 100% !important" data-date-format="yyyy-mm-dd"
                                                    data-date-start-date="+0d">
                                                    <input type="text" id="startDate" class="form-control"
                                                        name="start_at" data-name="start_at">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div> --}}

                                        <div class="form-group" id="endDateSection">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat_subscriptions.form.end_at') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="end_at" class="form-control" id="endDate"
                                                    data-name="end_at" value="" readonly>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{ __('apps::dashboard.general.add_btn') }}
                                    </button>
                                    <a href="{{ url(route('dashboard.baqat_subscriptions.index')) }}"
                                        class="btn btn-lg red">
                                        {{ __('apps::dashboard.general.back_btn') }}
                                    </a>
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
    <script>
        $(function() {
            $('#startDate').val(new Date().toJSON().slice(0, 10));

            $('#baqaSelect2').on('select2:select', function(e) {
                var data = e.params.data;
                var userId = $('#userSelect2').val();
                if (data.id != '' && userId != '') {
                    calculateEndDate(data.id, '', userId);
                } else {
                    $('#startDate').val('');
                    $('#endDate').val('');
                }
            });

            $('#userSelect2').on('select2:select', function(e) {
                var data = e.params.data;
                var baqaId = $('#baqaSelect2').val();
                if (data.id != '' && baqaId != '') {
                    calculateEndDate(baqaId, '', data.id);
                } else {
                    $('#startDate').val('');
                    $('#endDate').val('');
                }
            });

            $('#baqaSelect2, #userSelect2').on('select2:unselect', function(e) {
                var data = e.params.data;
                $('#endDate').val('');
            });
        });

        function calculateEndDate(id, startDate, userId) {
            let data = {
                'id': id,
                'start_at': startDate,
                'user_id': userId,
            };
            $.ajax({
                method: "GET",
                url: '{{ route('dashboard.baqat.calculate_end_date') }}',
                data: data,
                beforeSend: function() {
                    $('#startDateSection').hide();
                    $('#endDateSection').hide();
                },
                success: function(data) {},
                error: function(data) {
                    alert(data);
                },
                complete: function(data) {
                    var getJSON = $.parseJSON(data.responseText);
                    $('#startDate').val(getJSON.start_at);
                    $('#endDate').val(getJSON.end_at);
                    $('#startDateSection').show();
                    $('#endDateSection').show();
                },
            });
        }

        $('#startDate').on('change', function(e) {
            var baqaId = $('#baqaSelect2').val();
            var startDate = $(this).val();
            if (baqaId != '' && startDate != '') {
                calculateEndDate(baqaId, startDate);
            }
        });
    </script>
@stop
