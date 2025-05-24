@extends('apps::dashboard.layouts.app')
@section('title', __('report::dashboard.reports.index.form.late_orders.title'))
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
                        <a
                            href="{{ url(route('dashboard.reports.get_order_reports')) }}">{{ __('report::dashboard.reports.index.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('report::dashboard.reports.index.form.late_orders.title') }}</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">

                        {{-- DATATABLE FILTER --}}
                        <div class="row">
                            <div class="portlet box grey-cascade">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>
                                        {{ __('apps::dashboard.datatable.search') }}
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="padding: 27px 12px 10px !important;">
                                    <div id="filter_data_table">
                                        <div class="panel-body">

                                            <form id="formFilter" class="horizontal-form">
                                                <div class="form-body">

                                                    <div class="row">

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{ __('apps::dashboard.datatable.form.date_range') }}
                                                                </label>
                                                                <div id="reportrange" class="btn default form-control">
                                                                    <i class="fa fa-calendar"></i> &nbsp;
                                                                    <span> </span>
                                                                    <b class="fa fa-angle-down"></b>
                                                                    <input type="hidden" name="from">
                                                                    <input type="hidden" name="to">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="form-actions">
                                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom"
                                                    id="search">
                                                    <i class="fa fa-search"></i>
                                                    {{ __('apps::dashboard.datatable.search') }}
                                                </button>
                                                <button class="btn btn-sm red btn-outline filter-cancel">
                                                    <i class="fa fa-times"></i>
                                                    {{ __('apps::dashboard.datatable.reset') }}
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- END DATATABLE FILTER --}}

                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('report::dashboard.reports.index.form.late_orders.title') }}
                                </span>
                            </div>
                        </div>

                        {{-- DATATABLE CONTENT --}}
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <a href="javascript:;" onclick="CheckAll()">
                                                {{ __('apps::dashboard.general.select_all_btn') }}
                                            </a>
                                        </th>
                                        <th>#</th>
                                        <th>{{ __('report::dashboard.reports.index.form.late_orders.datatable.driver_name') }}
                                        </th>
                                        <th>{{ __('report::dashboard.reports.index.form.late_orders.datatable.delivery_time') }}
                                        </th>
                                        <th>{{ __('report::dashboard.reports.index.form.late_orders.datatable.driver_receiving_time') }}
                                        </th>
                                        <th>{{ __('report::dashboard.reports.index.form.late_orders.datatable.delay_time') }}
                                        </th>
                                        <th>{{ __('report::dashboard.reports.index.form.late_orders.datatable.created_at') }}
                                        </th>
                                        <th>{{ __('report::dashboard.reports.index.form.late_orders.datatable.options') }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script>
        function tableGenerate(data = '') {

            var dataTable =
                $('#dataTable').DataTable({

                    'fnDrawCallback': function(data) {
                        $('#count_orders').html(data.json.recordsTotal);
                        $('#sum_total_orders').html(data.json.recordsTotalSum);
                    },
                    "createdRow": function(row, data, dataIndex) {
                        if (data["deleted_at"] != null) {
                            $(row).addClass('danger');
                        }

                        if (data["unread"] == false) {
                            $(row).addClass('danger');
                        }
                    },
                    ajax: {
                        url: "{{ url(route('dashboard.reports.late_orders.datatable')) }}",
                        type: "GET",
                        data: {
                            req: data,
                        },
                    },
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/{{ ucfirst(LaravelLocalization::getCurrentLocaleName()) }}.json"
                    },
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    responsive: !0,
                    order: [
                        [1, "desc"]
                    ],
                    columns: [{
                            data: 'id',
                            className: 'dt-center'
                        },
                        {
                            data: 'id',
                            className: 'dt-center'
                        },
                        {
                            data: 'driver_name',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'delivery_time',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'driver_receiving_time',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'delay_time',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'created_at',
                            className: 'dt-center'
                        },
                        {
                            data: 'id'
                        },
                    ],
                    columnDefs: [{
                            targets: 0,
                            width: '30px',
                            className: 'dt-center',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                          <input type="checkbox" value="` + data + `" class="group-checkable" name="ids">
                          <span></span>
                        </label>
                      `;
                            },
                        },
                        {
                            targets: -1,
                            responsivePriority: 1,
                            width: '13%',
                            title: '{{ __('report::dashboard.reports.datatable.options') }}',
                            className: 'dt-center',
                            orderable: false,
                            render: function(data, type, full, meta) {

                                // Show
                                var showUrl =
                                    "{{ route('dashboard.orders.show', [':id', 'all_orders']) }}";
                                showUrl = showUrl.replace(':id', data);

                                var buttons = `
                                        @permission('show_orders')
                                            <a href="` + showUrl + `" class="btn btn-sm btn-warning" title="Show">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endpermission
                                        `;
                                return buttons;

                            },
                        },
                    ],
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, 100, 500],
                        ['10', '25', '50', '100', '500']
                    ],
                    buttons: [{
                            extend: "pageLength",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.pageLength') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: "print",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.print') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: "pdf",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.pdf') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: "excel",
                            className: "btn blue btn-outline ",
                            text: "{{ __('apps::dashboard.datatable.excel') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: "colvis",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.colvis') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5, 6]
                            }
                        }
                    ]
                });
        }

        jQuery(document).ready(function() {
            tableGenerate();

            $(".searchableSelect").select2({
                placeholder: "{{ __('apps::dashboard.datatable.form.select_option') }}",
                allowClear: true
            });
        });
    </script>

@stop
