@extends('apps::dashboard.layouts.app')
@section('title', __('user::dashboard.drivers.update.title'))
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
                        <a href="{{ url(route('dashboard.drivers.index')) }}">
                            {{ __('user::dashboard.drivers.index.title') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('user::dashboard.drivers.update.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" user="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.drivers.update', $user->id) }}">
                    @csrf
                    @method('PUT')
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
                                                        {{ __('user::dashboard.drivers.update.form.general') }}
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#statesTab" data-toggle="tab">
                                                        {{ __('user::dashboard.drivers.tabs.states') }}
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

                                        @if (config('setting.other.select_shipping_provider') == 'shipping_company')
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('user::dashboard.drivers.create.form.company') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="company_id" id="single" class="form-control select2"
                                                        data-name="company_id">
                                                        <option value=""></option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company['id'] }}"
                                                                {{ $company['id'] == $user->company_id ? 'selected' : '' }}>
                                                                {{ $company->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.name') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="name" class="form-control" data-name="name"
                                                    value="{{ $user->name }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.email') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="email" name="email" class="form-control" data-name="email"
                                                    value="{{ $user->email }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.mobile') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="mobile" class="form-control" data-name="mobile"
                                                    value="{{ $user->mobile }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.password') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="password" name="password" class="form-control"
                                                    data-name="password">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.confirm_password') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="password" name="confirm_password" class="form-control"
                                                    data-name="confirm_password">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.create.form.maximum_received_orders_count') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" name="maximum_received_orders_count"
                                                    class="form-control" data-name="maximum_received_orders_count"
                                                    value="{{ $user->maximum_received_orders_count }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.image') }}
                                            </label>
                                            <div class="col-md-9">
                                                @include('core::dashboard.shared.file_upload', [
                                                    'image' => $user->image,
                                                ])
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.users.is_verified') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="is_verified"
                                                    data-size="small" name="is_verified"
                                                    {{ $user->is_verified == 1 ? 'checked' : '' }}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.drivers.update.form.roles') }}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="mt-checkbox-list">
                                                    @foreach ($roles as $role)
                                                        <label class="mt-checkbox">
                                                            <input type="checkbox" name="roles[]"
                                                                value="{{ $role->id }}"
                                                                {{ $user->roles->contains($role->id) ? 'checked=""' : '' }}>
                                                            {{ $role->display_name }}
                                                            <span></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        @if ($user->trashed())
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('apps::dashboard.general.restore') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="restore">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="statesTab">
                                    <div class="tabbable tabbable-tabdrop">
                                        <ul class="nav nav-pills">

                                            @foreach ($activeCitiesWithStates as $key => $city)
                                                <li class="{{ $key == 0 ? 'active' : '' }}">
                                                    <a href="#cities_{{ $key }}" data-toggle="tab">
                                                        {{ $city->title }}
                                                    </a>
                                                </li>
                                            @endforeach

                                        </ul>
                                        <div class="tab-content">

                                            @foreach ($activeCitiesWithStates as $key2 => $city2)
                                                <div class="tab-pane {{ $key2 == 0 ? 'active' : '' }}"
                                                    id="cities_{{ $key2 }}">

                                                    <div class="col-md-5">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <th style="padding: 15px 5px 15px 0;">
                                                                        {{ __('user::dashboard.drivers.tabs.form.state') }}
                                                                    </th>
                                                                    <th style="width: 140px;">
                                                                        <div class="pull-right"
                                                                            title="{{ __('user::dashboard.drivers.tabs.form.btn.activate_all') }}">
                                                                            <input type="checkbox"
                                                                                class="make-switch makeAllActiveCheckbox"
                                                                                data-size="small"
                                                                                name="active_all_statuses">
                                                                        </div>
                                                                    </th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($city2->states as $key3 => $state)
                                                                        <tr>
                                                                            <td>{{ $state->title }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="checkbox"
                                                                                    class="make-switch status-input"
                                                                                    data-size="small"
                                                                                    {{ $user->driverStates->contains($state->id) ? 'checked' : '' }}
                                                                                    name="statuses[{{ $state->id }}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach

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
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{ __('apps::dashboard.general.edit_btn') }}
                                    </button>
                                    <a href="{{ url(route('dashboard.drivers.index')) }}" class="btn btn-lg red">
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
        jQuery(document).ready(function() {
            $('.makeAllActiveCheckbox').on('switchChange.bootstrapSwitch', function(e) {
                $('.makeAllActiveCheckbox').not(this).prop('checked', e.target.checked).change();
                $('.status-input').each(function(event) {
                    $(this).prop('checked', e.target.checked).change();
                });
            });
        });
    </script>
@stop
