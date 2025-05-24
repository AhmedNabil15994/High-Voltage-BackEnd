@extends('apps::dashboard.layouts.app')
@section('title', __('baqat::dashboard.baqat_subscriptions.show.title'))
@section('content')
    <style type="text/css">
        .table>thead>tr>th {
            border-bottom: none !important;
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
                        <a href="#">{{ __('baqat::dashboard.baqat_subscriptions.show.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <div class="col-md-12">
                    <div class="no-print">
                        <div class="col-md-3">
                            <ul class="ver-inline-menu tabbable margin-bottom-10">

                                <li class="active">
                                    <a data-toggle="tab" href="#subscription_details">
                                        <i class="fa fa-cog"></i>
                                        {{ __('baqat::dashboard.baqat_subscriptions.show.tabs.subscription_details') }}
                                    </a>
                                    <span class="after"></span>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#subscription_transaction">
                                        <i class="fa fa-cog"></i>
                                        {{ __('baqat::dashboard.baqat_subscriptions.show.tabs.subscription_transaction') }}
                                    </a>
                                    <span class="after"></span>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9 contentPrint">
                        <div class="tab-content">

                            <div class="tab-pane active" id="subscription_details">
                                <div class="invoice-content-2 bordered">
                                    <div class="row invoice-head">
                                        <div class="col-md-2 col-xs-2">
                                            <div class="invoice-logo">
                                                <center>
                                                    <img src="{{ url(config('setting.images.logo')) }}"
                                                        class="img-responsive" alt=""
                                                        style="width: 100px; height: auto; margin-bottom: 10px" />
                                                    <span>
                                                        {{ ucfirst(optional($baqatSubscription->transaction)->method) }}
                                                    </span>
                                                </center>
                                            </div>
                                        </div>

                                        @if ($baqatSubscription->baqa != null)
                                            <div class="col-md-5 col-xs-5">

                                                <span
                                                    class="bold">{{ __('baqat::dashboard.baqat_subscriptions.show.baqa.id') }}
                                                    :
                                                </span>
                                                {{ $baqatSubscription->baqa->id }}
                                                <br />

                                                <span
                                                    class="bold">{{ __('baqat::dashboard.baqat_subscriptions.show.baqa.title') }}
                                                    :
                                                </span>
                                                {{ $baqatSubscription->baqa->title }}
                                                <br />

                                                <span
                                                    class="bold">{{ __('baqat::dashboard.baqat_subscriptions.show.baqa.duration_description') }}
                                                    :
                                                </span>
                                                {{ $baqatSubscription->baqa->duration_description }}
                                                <br />

                                                <span
                                                    class="bold">{{ __('baqat::dashboard.baqat_subscriptions.show.baqa.duration_by_days') }}
                                                    :
                                                </span>
                                                {{ $baqatSubscription->baqa->duration_by_days }}
                                                <br />

                                            </div>
                                        @endif

                                        <div class="col-md-5 col-xs-5">
                                            <div class="company-address">
                                                <h6 class="uppercase">#{{ $baqatSubscription['id'] }}</h6>
                                                <h6 class="uppercase">
                                                    {{ date('Y-m-d / H:i:s', strtotime($baqatSubscription->created_at)) }}
                                                </h6>
                                                <span class="bold">
                                                    {{ __('baqat::dashboard.baqat_subscriptions.show.user.name') }} :
                                                </span>
                                                {{ $baqatSubscription->user->name ?? '---' }}
                                                <br />
                                                <span class="bold">
                                                    {{ __('baqat::dashboard.baqat_subscriptions.show.user.mobile') }} :
                                                </span>
                                                {{ $baqatSubscription->user->mobile ?? '---' }}
                                                <br />
                                            </div>
                                        </div>

                                        <div class="row invoice-body">
                                            <div class="col-xs-12 table-responsive">
                                                <br>
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="invoice-title uppercase text-left">
                                                                {{ __('baqat::dashboard.baqat_subscriptions.show.items.id') }}
                                                            </th>
                                                            <th class="invoice-title uppercase text-left">
                                                                {{ __('baqat::dashboard.baqat_subscriptions.show.items.price') }}
                                                            </th>
                                                            <th class="invoice-title uppercase text-left">
                                                                {{ __('baqat::dashboard.baqat_subscriptions.show.items.type') }}
                                                            </th>
                                                            <th class="invoice-title uppercase text-left">
                                                                {{ __('baqat::dashboard.baqat_subscriptions.show.items.start_at') }}
                                                            </th>
                                                            <th class="invoice-title uppercase text-left">
                                                                {{ __('baqat::dashboard.baqat_subscriptions.show.items.end_at') }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="notbold text-left">
                                                                {{ $baqatSubscription->id }}
                                                            </td>
                                                            <td class="text-left notbold">
                                                                {{ $baqatSubscription->price }}
                                                            </td>
                                                            <td class="text-left notbold">
                                                                {{ __('baqat::dashboard.baqat_subscriptions.show.items.type_info.' . $baqatSubscription->type) }}
                                                            </td>
                                                            <td class="text-left notbold">
                                                                {{ $baqatSubscription->start_at }}</td>
                                                            <td class="text-left notbold"> {{ $baqatSubscription->end_at }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="subscription_transaction">
                                <div class="invoice">

                                    <div class="row">
                                        <div class="col-xs-12 table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('transaction::dashboard.orders.show.transaction.payment_id') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('transaction::dashboard.orders.show.transaction.track_id') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('transaction::dashboard.orders.show.transaction.method') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('transaction::dashboard.orders.show.transaction.result') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('transaction::dashboard.orders.show.transaction.ref') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('transaction::dashboard.orders.show.transaction.tran_id') }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($baqatSubscription->transaction)
                                                        <tr>
                                                            <td class="text-center sbold">
                                                                {{ $baqatSubscription->transaction->payment_id }}</td>
                                                            <td class="text-center sbold">
                                                                {{ $baqatSubscription->transaction->track_id }}</td>
                                                            <td class="text-center sbold">
                                                                {{ $baqatSubscription->transaction->method }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ $baqatSubscription->transaction->result }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ $baqatSubscription->transaction->ref }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ $baqatSubscription->transaction->tran_id }}</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();">
                            {{ __('apps::dashboard.general.print_btn') }}
                            <i class="fa fa-print"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@stop

@section('scripts')
    <script></script>
@endsection
