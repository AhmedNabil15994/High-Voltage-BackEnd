@extends('apps::dashboard.layouts.app')
@section('title', __('baqat::dashboard.baqat.routes.create'))
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
                        <a href="{{ url(route('dashboard.baqat.index')) }}">
                            {{ __('baqat::dashboard.baqat.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('baqat::dashboard.baqat.routes.create') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.baqat.store') }}">
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
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('baqat::dashboard.baqat.form.tabs.general') }}
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#priceTab" data-toggle="tab">
                                                        {{ __('baqat::dashboard.baqat.form.tabs.price') }}
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

                                        <ul class="nav nav-tabs">
                                            @foreach (config('translatable.locales') as $code)
                                                <li class="@if ($loop->first) active @endif">
                                                    <a data-toggle="tab"
                                                        href="#first_{{ $code }}">{{ __('baqat::dashboard.baqat.form.tabs.input_lang', ['lang' => $code]) }}</a>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="tab-content">

                                            @foreach (config('translatable.locales') as $code)
                                                <div id="first_{{ $code }}"
                                                    class="tab-pane fade @if ($loop->first) in active @endif">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('baqat::dashboard.baqat.form.title') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title[{{ $code }}]"
                                                                class="form-control" data-name="title.{{ $code }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('baqat::dashboard.baqat.form.description') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="description[{{ $code }}]" rows="4" class="form-control"
                                                                data-name="description.{{ $code }}"></textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('baqat::dashboard.baqat.form.duration_description') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text"
                                                                name="duration_description[{{ $code }}]"
                                                                class="form-control"
                                                                data-name="duration_description.{{ $code }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat.form.duration_by_days') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" name="duration_by_days" class="form-control"
                                                    data-name="duration_by_days" value="">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat.form.sort') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" name="sort" class="form-control" data-name="sort"
                                                    value="0">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat.form.status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="status" data-size="small"
                                                    name="status">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="priceTab">

                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat.form.price') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" step="0.1" min="0" name="price"
                                                    class="form-control" data-name="price" value="">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat.form.client_price') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" step="0.1" min="0" name="client_price"
                                                       class="form-control" data-name="client_price" value="">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('baqat::dashboard.baqat.form.add_offer_status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="offerStatusCheckbox"
                                                    data-size="small" name="offer_status">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="offer-form" style="display:none;">

                                            <div class="form-group">
                                                <label
                                                    class="col-md-2">{{ __('baqat::dashboard.baqat.form.offer_type.label') }}</label>
                                                <div class="col-md-9">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio">
                                                            <input type="radio" name="offer_type"
                                                                id="offerAmountRadioBtn" value="amount"
                                                                onclick="toggleOfferType('amount')" checked="">
                                                            {{ __('baqat::dashboard.baqat.form.offer_type.amount') }}
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="offer_type"
                                                                id="offerPercentageRadioBtn" value="percentage"
                                                                onclick="toggleOfferType('percentage')">
                                                            {{ __('baqat::dashboard.baqat.form.offer_type.percentage') }}
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="offerAmountSection">
                                                <label class="col-md-2">
                                                    {{ __('baqat::dashboard.baqat.form.offer_price') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number" step="0.1" min="0" id="offer-form"
                                                        name="offer_price" class="form-control" data-name="offer_price">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="offerPercentageSection" style="display: none">
                                                <label class="col-md-2">
                                                    {{ __('baqat::dashboard.baqat.form.percentage') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number" step="0.1" min="0"
                                                        id="offer-percentage-form" name="offer_percentage"
                                                        class="form-control" data-name="offer_percentage">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('baqat::dashboard.baqat.form.start_at') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-medium date date-picker"
                                                        style="width: 100% !important;" data-date-format="yyyy-mm-dd"
                                                        data-date-start-date="+0d">
                                                        <input type="text" id="offer-form" class="form-control"
                                                            name="start_at" data-name="start_at">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div class="help-block" style="color: #e73d4a !important;"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('baqat::dashboard.baqat.form.end_at') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-medium date date-picker"
                                                        style="width: 100% !important;" data-date-format="yyyy-mm-dd"
                                                        data-date-start-date="+0d">
                                                        <input type="text" id="offer-form" class="form-control"
                                                            name="end_at" data-name="end_at">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div class="help-block" style="color: #e73d4a !important;"></div>
                                                </div>
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
                                    <a href="{{ url(route('dashboard.baqat.index')) }}" class="btn btn-lg red">
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

            $('#offerStatusCheckbox').on('switchChange.bootstrapSwitch', function(e) {
                if (e.target.checked == true) {
                    $("input#offer-form").prop("disabled", false);
                    $('.offer-form').css('display', '');
                } else {
                    $("input#offer-form").prop("disabled", true);
                    $('.offer-form').css('display', 'none');
                }
            });

        });

        function toggleOfferType(type = '') {
            if (type === 'amount') {
                $('#offerAmountSection').show();
                $('#offerPercentageSection').hide();
            } else if (type === 'percentage') {
                $('#offerPercentageSection').show();
                $('#offerAmountSection').hide();
            }
        }
    </script>
@stop
