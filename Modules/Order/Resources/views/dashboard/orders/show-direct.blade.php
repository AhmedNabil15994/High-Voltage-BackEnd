@extends('apps::dashboard.layouts.app')
@section('title', __('order::dashboard.orders.show.title'))
@section('css')
    <style>
        .btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg {

            padding: 12px 20px 10px;
        }

        .hide_admin_tag,
        .hide_client_tag {
            display: none;
        }

        .well {
            box-shadow: none;
        }
        .border-bottom{
            margin-bottom: 20px;
            border-bottom: 1px dashed #555;
        }
        .thermal-invoice h3{
            display: block;
            margin-bottom: 10px;
            font-weight: 900;
        }
        .thermal-invoice hr{
            border-style: dashed;
            border-color: #000;
        }
        .thermal-invoice h3{
            font-weight: bold;
        }
        .pre-line{
            white-space: pre-line;
        }
        .secondRow{
            display: block;
            padding-left: 20px;
            padding-right: 20px;
            margin-bottom: 10px !important;
        }
        .tableRow{
            margin-bottom: 10px;
            font-size: 18px;
        }
        .p-15{
            padding-left: 15px;
            padding-right: 15px;
        }
        .p-20{
            padding-left: 20px;
            padding-right: 20px;
        }
        .m-5{
            margin: 5px 0 10px 0;
        }
        .lastProduct{
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        .w-100{
            width: 100%;
        }
        hr.w-100:not(.m-10){
            margin-bottom: 5px;
        }
        .firstRow,.orderPayment{
            padding-bottom: 10px;
        }
        .m-10{
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }
        .mb-10{
            margin-bottom: 10px !important;
        }
        .firstRow h4{
            letter-spacing: 2px;
        }
        .itemsTable{
            overflow: hidden;
        }
    </style>
    <style>
        .btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg {

            padding: 12px 20px 10px;
        }

        .hide_admin_tag,
        .hide_client_tag {
            display: none;
        }

        .well {
            box-shadow: none;
        }
    </style>

@stop

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
                        <a href="{{ url(route('dashboard.' . $flag . '.index')) }}">
                            {{ __('order::dashboard.orders.flags.' . $flag) }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('order::dashboard.orders.show.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            @include('apps::frontend.layouts._alerts')

            <div class="row">
                <div class="col-md-8">
                    <div class="thermal-invoice">
                        <div class="row text-center firstRow border-bottom">
                            <img width="150px" height="150px" src="{{asset('frontend/assets/images/logo_slogan.svg')}}">
                            <h4>{{$order->orderStatus->title}}</h4>
                            @if($order->driver)
                            <h4>Driver : {{$order->driver->driver->name}}</h4>
                            @endif
                            <h3>High Voltage Laundry</h3>
                            <h3 class="pre-line">Address : AlFarwaniyah Block 6 Street 4
                                Building 1123</h3>
                            <h3>Mobile / Tel : {{config('setting.contact_us.mobile')}}</h3>
                        </div>
                        <div class="row secondRow border-bottom">
                            <div class="row tableRow">
                                <div class="col-xs-6">
                                    <div class="col-xs-6">
                                        <b>Order ID :  </b>
                                    </div>
                                    <div class="col-xs-6">{{$order->id}}</div>
                                </div>
                                @if((int) $order->user->subscriptions_balance)
                                    <div class="col-xs-6">
                                        <div class="col-xs-6">
                                            <b>Remaining Balance : </b>
                                        </div>
                                        <div class="col-xs-6">{{$order->user->subscriptions_balance . ' ' .__('KD')}}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="row tableRow">
                                <div class="col-xs-12">
                                    <div class="col-xs-3">
                                        <b>Date :</b>
                                    </div>
                                    <div class="col-xs-9">{{date('Y-m-d A h:i',strtotime($order->created_at))}}</div>
                                </div>
                            </div>

                            <div class="row tableRow">
                                <div class="col-xs-12">
                                    <div class="col-xs-3">
                                        <b>Customer :</b>
                                    </div>
                                    <div class="col-xs-9">{{$order->orderAddress->username}}</div>
                                </div>
                            </div>

                            @if ($order->orderAddress != null)
                                <div class="col-md-6 col-xs-6">
                                    <div class="note well">
                                        @if (!is_null($order->orderAddress->state))
                                            <span class="bold uppercase">
                                                    {{ $order->orderAddress->state->city->title }}
                                                    /
                                                    {{ $order->orderAddress->state->title }}
                                                </span>
                                        @endif
                                        <br />

                                        @if ($order->orderAddress->governorate)
                                            <span
                                                class="bold">{{ __('order::dashboard.orders.show.address.governorate') }}
                                                    :
                                                </span>
                                            {{ $order->orderAddress->governorate }}
                                            <br />
                                        @endif

                                        @if ($order->orderAddress->block)
                                            <span class="bold">{{ __('order::dashboard.orders.show.address.block') }}
                                                    :
                                                </span>
                                            {{ $order->orderAddress->block }}
                                            <br />
                                        @endif

                                        @if ($order->orderAddress->district)
                                            <span
                                                class="bold">{{ __('order::dashboard.orders.show.address.district') }}
                                                    :
                                                </span>
                                            {{ $order->orderAddress->district }}
                                            <br />
                                        @endif

                                        @if ($order->orderAddress->street)
                                            <span
                                                class="bold">{{ __('order::dashboard.orders.show.address.street') }}
                                                    :
                                                </span>
                                            {{ $order->orderAddress->street }}
                                            <br />
                                        @endif

                                        @if ($order->orderAddress->building)
                                            <span
                                                class="bold">{{ __('order::dashboard.orders.show.address.building') }}
                                                    :
                                                </span>
                                            {{ $order->orderAddress->building }}
                                            <br />
                                        @endif

                                        @if ($order->orderAddress->floor)
                                            <span class="bold">{{ __('order::dashboard.orders.show.address.floor') }}
                                                    :
                                                </span>
                                            {{ $order->orderAddress->floor }}
                                            <br />
                                        @endif

                                        @if ($order->orderAddress->flat)
                                            <span class="bold">{{ __('order::dashboard.orders.show.address.flat') }}
                                                    : </span>
                                            {{ $order->orderAddress->flat }}
                                            <br />
                                        @endif

                                        <span class="bold">{{ __('order::dashboard.orders.show.address.details') }}
                                                :
                                            </span>
                                        {{ $order->orderAddress->address ?? '---' }}
                                    </div>
                                </div>
                            @endif

                            <div class="row tableRow">
                                <div class="col-xs-12">
                                    <div class="col-xs-3">
                                        <b>Phone :</b>
                                    </div>
                                    <div class="col-xs-9">+{{(substr($order->orderAddress->mobile, 0, 3) == '965' ? '' : '965').$order->orderAddress->mobile}}</div>
                                </div>
                            </div>

                            <div class="row tableRow">
                                <div class="col-xs-12">
                                    <div class="col-xs-3">
                                        <b>Pickup :</b>
                                    </div>
                                    @php
                                        $pickup_times = explode('-',$order->orderTimes->receiving_data['receiving_time']);
                                        $delievery_times = explode('-',$order->orderTimes->delivery_data['delivery_time']);
                                    @endphp
                                    <div class="col-xs-9">{{$order->orderTimes->receiving_data['receiving_date']}} {{date('A h:i',strtotime($pickup_times[0])) . ' - ' . date('A h:i',strtotime($pickup_times[1]))}} </div>
                                </div>
                            </div>

                            <div class="row tableRow">
                                <div class="col-xs-12">
                                    <div class="col-xs-3">
                                        <b>Delivery :</b>
                                    </div>
                                    <div class="col-xs-9">{{$order->orderTimes->delivery_data['delivery_date']}} {{date('A h:i',strtotime($delievery_times[0])) . ' - ' . date('A h:i',strtotime($delievery_times[1]))}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="itemsTable">
                            <div class="row p-20">
                                <div class="col-xs-3">
                                    <b>{{__('order::dashboard.orders.show.invoices.item')}}</b>
                                </div>
                                <div class="col-xs-3">
                                    <b>{{__('order::dashboard.orders.show.invoices.qunatity')}}</b>
                                </div>
                                <div class="col-xs-3">
                                    <b>{{__('order::dashboard.orders.show.invoices.item_price')}}</b>
                                </div>
                                <div class="col-xs-3">
                                    <b>{{__('order::dashboard.orders.show.invoices.amount')}}</b>
                                </div>
                            </div>
                            <hr style="margin-bottom: 10px;margin-top: 10px;">
                            @php $count = 0; @endphp
                            @foreach($orderProducts as $key => $addons)
                                <div class="row p-20">
                                    <div class="col-xs-3">
                                        @if(!empty($addons))
                                            <b>
                                                {{$addons[0]->addon->getTranslations('title')['en']}}<br>
                                                {{$addons[0]->addon->getTranslations('title')['ar']}}<br>
                                            </b>
                                            <br>
                                        @endif
                                        @foreach($addons as $singleAddon)
                                            @if(!empty($singleAddon))
                                                <p class="m-5">
                                                    {{$singleAddon->orderProduct->product->getTranslations('title')['en']}}<br>
                                                    {{$singleAddon->orderProduct->product->getTranslations('title')['ar']}}<br>
                                                </p>
                                            @endif
                                        @endforeach

                                    </div>
                                    <div class="col-xs-3">
                                        <br><br>
                                        @foreach($addons as $singleAddon)
                                            @php $count++; @endphp
                                            @if(!empty($singleAddon))
                                                <br>
                                                <p class="m-5">{{$singleAddon->qty}}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-xs-3">
                                        <br><br>
                                        @foreach($addons as $singleAddon)
                                            @php $count++; @endphp
                                            @if(!empty($singleAddon))
                                                <br>
                                                <p class="m-5">{{number_format($singleAddon->total / $singleAddon->qty,3)}}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-xs-3">
                                        <br><br>
                                        @foreach($addons as $singleAddon)
                                            @if(!empty($singleAddon))
                                                <br>
                                                <p class="m-5">{{$singleAddon->total}}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="w-100 p-15">
                                        <hr class="w-100">
                                    </div>
                                </div>
                            @endforeach

                            <div class="row p-20 border-bottom mb-10">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <p class="m-5">{{$count}}</p>
                                </div>
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3 ">
                                    <p class="m-5">{{$order->subtotal . ' ' .__('KD')}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="orderSummary">
                            <div class="row p-20">
                                <div class="col-xs-9">{{__('order::dashboard.orders.show.invoices.subtotal')}} :</div>
                                <div class="col-xs-3"> {{$order->subtotal . ' ' .__('KD')}}</div>
                            </div>
                            <div class="row p-20">
                                <div class="col-xs-9">{{__('order::dashboard.orders.show.invoices.delievery_fee')}} :</div>
                                <div class="col-xs-3">{{$order->shipping . ' ' .__('KD')}}</div>
                            </div>
                            <div class="row p-20">
                                <div class="col-xs-9">{{__('order::dashboard.orders.show.invoices.discount')}} :</div>
                                <div class="col-xs-3">{{$order->discount ?? '0.000' . ' ' .__('KD')}}</div>
                            </div>
                            <div class="row p-20">
                                <div class="col-xs-9">
                                    <b>{{__('order::dashboard.orders.show.invoices.total')}} :</b>
                                </div>
                                <div class="col-xs-3 mb-5">
                                    <b>{{$order->total . ' ' .__('KD')}}</b>
                                </div>
                            </div>
                            <div class="w-100 p-15">
                                <hr class="w-100 m-10">
                            </div>
                        </div>

                        <div class="orderPayment border-bottom">
                            <div class="row p-20">
                                <div class="col-xs-9">
                                    <b>{{__('order::dashboard.orders.show.invoices.payment')}} :</b>
                                </div>
                                <div class="col-xs-3 mb-5">
                                    <b>{{$order->paymentStatus ? $order->paymentStatus->flag : __('order::dashboard.orders.show.invoices.not_paid')}}</b>
                                </div>
                            </div>
                        </div>

                        <div class="terms p-20">
                            <h2>{{__('order::dashboard.orders.show.invoices.terms')}}</h2>
                            <p class="pre-line">{{$termsPage->getTranslation('seo_description', 'ar')}}</p>
                            <p class="pre-line">{{$termsPage->getTranslation('seo_description', 'en')}}</p>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 no-print">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered" style="border: 1px solid #e7ecf1!important">

                        <div class="portlet-body">
                            <div class="row">

                                <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                    onclick="javascript:window.print();">
                                    {{ __('apps::dashboard.general.print_btn') }}
                                    <i class="fa fa-print"></i>
                                </a>


                                @if (is_null($order->payment_confirmed_at) && in_array($order->orderStatus->flag,['is_ready','new_order','on_the_way']))
                                    @permission('confirm_payment_order')
                                        {{-- <div class="col-md-3"> --}}
                                        <a class="btn btn-lg btn-success hidden-print margin-bottom-5" href="#confirm_payment"
                                            data-toggle="modal">
                                            {{ __('order::dashboard.orders.show.confirm_payment') }}
                                        </a>
                                        <div class="modal fade" id="confirm_payment" tabindex="-1" role="basic"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"></button>

                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('order::dashboard.orders.show.confirm_payment_dec') }}

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark btn-outline"
                                                            data-dismiss="modal">

                                                            {{ __('order::dashboard.orders.show.close') }}
                                                        </button>
                                                        <a href="{{ route('dashboard.orders.confirm.payment', $order->id) }}"
                                                            class="btn green">
                                                            {{ __('order::dashboard.orders.show.confirm_payment') }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        {{-- </div> --}}
                                    @endpermission
                                @endif
                            @if($order->orderStatus->flag == 'new_order')
                                @if (optional($order->paymentStatus)->flag != 'failed')
                                    @permission('cancel_payment_order')
                                        {{-- <div class="col-md-3"> --}}
                                        <a class="btn btn-lg btn-danger hidden-print margin-bottom-5" href="#cancel_payment"
                                            data-toggle="modal">
                                            {{ __('order::dashboard.orders.show.cancel_payment') }}
                                        </a>
                                        <div class="modal fade" id="cancel_payment" tabindex="-1" role="basic"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"></button>

                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('order::dashboard.orders.show.cancel_payment_dec') }}

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark btn-outline"
                                                            data-dismiss="modal">

                                                            {{ __('order::dashboard.orders.show.close') }}
                                                        </button>
                                                        <a href="{{ route('dashboard.orders.cancel.payment', $order->id) }}"
                                                            class="btn green">
                                                            {{ __('order::dashboard.orders.show.cancel_payment') }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        {{-- </div> --}}
                                    @endpermission
                                @endif

                                <a class="btn btn-lg btn-info hidden-print margin-bottom-5"
                                    href="{{ route('dashboard.orders.change_order_status_to_received', $order->id) }}">
                                    {{ __('order::dashboard.orders.show.order_is_received') }}
                                </a>

                            @elseif($order->orderStatus->flag == 'received')
                                <a class="btn btn-lg btn-warning hidden-print margin-bottom-5"
                                    href="{{ route('dashboard.orders.change_order_status_to_processing', $order->id) }}">
                                    {{ __('order::dashboard.orders.show.order_is_processing') }}
                                </a>
                            @elseif($order->orderStatus->flag == 'processing')
                                <a class="btn btn-lg btn-primary hidden-print margin-bottom-5"
                                    href="{{ route('dashboard.orders.change_order_status_to_ready', $order->id) }}">
                                    {{ __('order::dashboard.orders.show.order_is_ready') }}
                                </a>
                            @elseif($order->orderStatus->flag == 'is_ready')
                                <a class="btn btn-lg btn-info hidden-print margin-bottom-5"
                                    href="{{ route('dashboard.orders.change_order_status_to_on_the_way', $order->id) }}">
                                    {{ __('order::dashboard.orders.show.order_is_on_the_way') }}
                                </a>
                            @elseif($order->orderStatus->flag == 'on_the_way')
                                <a class="btn btn-lg btn-success hidden-print margin-bottom-5"
                                    href="{{ route('dashboard.orders.change_order_status_to_delivered', $order->id) }}">
                                    {{ __('order::dashboard.orders.show.order_is_delivered') }}
                                </a>
                            @endif
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>

                    @permission('show_order_change_status_tab')
                        {{-- <div class="portlet light bordered" style="    border: 1px solid #e7ecf1!important">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-shopping-cart font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">
                                        {{ __('order::dashboard.orders.show.change_order_status') }}
                                    </span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="no-print">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <form id="updateForm" method="POST"
                                                action="{{ url(route('dashboard.orders.update_order_status', $order['id'])) }}"
                                                enctype="multipart/form-data" class="horizontal-form">
                                                @csrf
                                                <input name="_method" type="hidden" value="PUT">

                                                <div class="form-group">
                                                    <label>
                                                        {{ __('order::dashboard.orders.show.drivers.title') }}
                                                    </label>
                                                    <select name="user_id" class="form-control">
                                                        <option value="">
                                                            --- {{ __('order::dashboard.orders.show.drivers.title') }}
                                                            ---
                                                        </option>
                                                        @foreach ($drivers as $driver)
                                                            <option value="{{ $driver->id }}"
                                                                @if ($order->driver) {{ $order->driver->user_id == $driver->id ? 'selected' : '' }} @endif>

                                                                {{ $driver->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>
                                                        {{ __('order::dashboard.orders.show.status') }}
                                                    </label>
                                                    <select name="order_status" id="single" class="form-control">
                                                        <option value="">--- Select ---</option>
                                                        @foreach ($statuses as $status)
                                                            <option value="{{ $status->id }}"
                                                                {{ $order->order_status_id == $status->id ? 'selected' : '' }}>

                                                                {{ $status->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>
                                                        {{ __('order::dashboard.orders.show.order_notes') }}
                                                    </label>
                                                    <textarea class="form-control" name="order_notes" rows="8" cols="80">{{ $order->order_notes }}</textarea>
                                                </div>


                                                <div id="result" style="display: none"></div>
                                                <div class="progress-info" style="display: none">
                                                    <div class="progress">
                                                        <span class="progress-bar progress-bar-warning"></span>
                                                    </div>
                                                    <div class="status" id="progress-status"></div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" id="submit" class="btn green btn-lg">
                                                        {{ __('apps::dashboard.general.edit_btn') }}
                                                    </button>
                                                </div>
                                            </form>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    @endpermission

                    @if (!is_null($order->payment_confirmed_at))
                        <div class="note note-success">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            {{ __('order::dashboard.orders.show.payment_confirmed_at') }} :
                                            {{ $order->payment_confirmed_at ? date('Y-m-d / H:i:s', strtotime($order->payment_confirmed_at)) : '---' }}
                                        </li>
                                        <li class="list-group-item">
                                            {{ __('order::dashboard.orders.show.payment_type') }} :
                                            {{ optional($order->paymentStatus)->flag }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @permission('show_order_change_status_tab')

                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <div class="portlet light bordered" style="    border: 1px solid #e7ecf1!important">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-archive font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">
                                        {{ __('order::dashboard.orders.show.order_history_log') }}
                                    </span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="no-print row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.order_status') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.updated_by') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.date') }}
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->orderStatusesHistory()->orderBy('pivot_created_at', 'desc')->get() as $k => $history)
                                                        <tr id="orderHistory-{{ optional($history->pivot)->id }}">
                                                            <td class="text-center sbold">
                                                                {{ $history->title ?? '' }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ is_null(optional($history->pivot)->user_id) ? '---' : \Modules\User\Entities\User::find(optional($history->pivot)->user_id)->name ?? null }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ optional($history->pivot)->created_at }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endpermission
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        function toggleAdminTag() {
            $('.hide_admin_tag').toggle();
            $('.show_admin_tag').toggle();
        }

        function toggleClientNotes() {
            $('.hide_client_tag').toggle();
            $('.show_client_tag').toggle();
        }

        function requestUpdating(status, data) {

            if (status == 'success') {

                if (data.data.type == 'admin') {
                    $('#admin_note').text("").append(data.data.note);
                    toggleAdminTag();
                } else {
                    $('#client_note').text("").append(data.data.note);
                    toggleClientNotes();
                }

            }
        }
    </script>

@endsection
