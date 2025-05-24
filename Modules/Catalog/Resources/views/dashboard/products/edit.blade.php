@extends('apps::dashboard.layouts.app')
@section('title', __('catalog::dashboard.products.routes.update'))
@inject('productFlags', 'Modules\Catalog\Enums\ProductFlag')

@section('css')
    <script src="/admin/assets/global/plugins/category-tree/tree.js?v=7.3.9" type="text/javascript"></script>
    <link rel="stylesheet" href="/admin/assets/global/plugins/category-tree/tree.css?v=7.3.9">
    <style>
        .btn-file-upload {
            position: relative;
            overflow: hidden;
        }

        .btn-file-upload input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        .img-preview {
            height: auto;
            /*width: 15%;*/
            /*width: 77%;*/
            /*height: 200px;*/
            /*display: none;*/
        }

        .upload-input-name {
            width: 75% !important;
        }

        .btnRemoveMore {
            margin: 0 5px;
        }

        .btnAddMore {
            margin: 7px 0;
        }

        .prd-image-section {
            margin-bottom: 10px;
        }

        .manageQty {
            width: 18px;
            height: 18px;
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
                        <a href="{{ url(route('dashboard.products.index')) }}">
                            {{ __('catalog::dashboard.products.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('catalog::dashboard.products.routes.update') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            @permission('add_products')
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{ url(route('dashboard.products.clone', $product->id)) }}" class="btn sbold green">
                                    <i class="fa fa-plus"></i> {{ __('apps::dashboard.general.clone') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endpermission

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.products.update', $product->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">

                        @if (config('setting.products.toggle_variations') == 1)
                            <div class="form-check text-center">
                                <div class="mt-radio-inline">
                                    @foreach ($productFlags::getConstList() as $flag)
                                        <label class="mt-radio">
                                            <input type="radio" name="product_flag" value="{{ $flag }}"
                                                onclick="onProductFlagChange('{{ $flag }}');"
                                                {{ $flag == $product->product_flag ? 'checked' : '' }}>
                                            {{ __('catalog::dashboard.products.form.' . $flag) }}
                                            <span></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="product_flag" value="{{ $productFlags::__default }}">
                        @endif

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
                                                        {{ __('catalog::dashboard.products.form.tabs.general') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#addons" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.addons') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#categories" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.categories') }}
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#seo" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.seo') }}
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

                                    <ul class="nav nav-tabs">
                                        @foreach (config('translatable.locales') as $code)
                                            <li class="@if ($loop->first) active @endif">
                                                <a data-toggle="tab"
                                                    href="#global_setting{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">

                                        @foreach (config('translatable.locales') as $code)
                                            <div id="global_setting{{ $code }}"
                                                class="tab-pane fade @if ($loop->first) in active @endif">

                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.title') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title[{{ $code }}]"
                                                                class="form-control" data-name="title.{{ $code }}"
                                                                {{ auth()->user()->can('edit_products_title')? '': 'disabled' }}
                                                                value="{{ $product->getTranslation('title', $code) }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.description') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="description[{{ $code }}]" rows="8" cols="80"
                                                                class="form-control {{ is_rtl($code) }}Editor" data-name="description.{{ $code }}">{{ $product->getTranslation('description', $code) }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.short_description') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="short_description[{{ $code }}]" rows="8" cols="80" class="form-control"
                                                                {{ auth()->user()->can('edit_products_description')? '': 'disabled' }}
                                                                data-name="short_description.{{ $code }}">{{ $product->getTranslation('short_description', $code) }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div> --}}

                                                </div>

                                            </div>
                                        @endforeach

                                        <div class="col-md-10">

                                            {{-- <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.sku') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="sku" class="form-control"
                                                        data-name="sku"
                                                        value="{{ $product->sku ?? generateRandomCode() }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div> --}}

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.image') }}
                                                </label>
                                                <div class="col-md-9">
                                                    @if (auth()->user()->can('edit_products_image'))
                                                        @include('core::dashboard.shared.file_upload', [
                                                            'image' => $product->image,
                                                        ])
                                                    @else
                                                        <span class="holder" style="margin-top:15px;max-height:100px;">
                                                            <img src="{{ url($product->image) }}" alt=""
                                                                style="height: 15rem;">
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.sort') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="sort" class="form-control"
                                                        data-name="sort" value="{{ $product->sort }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.status') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="status"
                                                        {{ $product->status == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.datatable.is_published') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small" name="is_published"
                                                        {{ $product->is_published == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            @if ($product->trashed())
                                                <div class="form-group">
                                                    <label class="col-md-2">
                                                        {{ __('apps::dashboard.general.restore') }}
                                                    </label>
                                                    <div class="col-md-9">
                                                        <input type="checkbox" class="make-switch" id="restore"
                                                            data-size="small" name="restore">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                </div>

                                <div class="tab-pane fade in" id="addons">
                                    @include('catalog::dashboard.products._custom_addons')
                                </div>

                                <div class="tab-pane fade in" id="categories">
                                    <div id="jstree">

                                        @include('catalog::dashboard.tree.products.edit', [
                                            'mainCategories' => $mainCategories,
                                        ])
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="category_id" id="root_category" value=""
                                            data-name="category_id">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="seo">

                                    <ul class="nav nav-tabs">
                                        @foreach (config('translatable.locales') as $code)
                                            <li class="@if ($loop->first) active @endif">
                                                <a data-toggle="tab"
                                                    href="#seo_{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">

                                        @foreach (config('translatable.locales') as $code)
                                            <div id="seo_{{ $code }}"
                                                class="tab-pane fade @if ($loop->first) in active @endif">

                                                <div class="col-md-10">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.meta_keywords') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="seo_keywords[{{ $code }}]" rows="8" cols="80" class="form-control"
                                                                data-name="seo_keywords.{{ $code }}">{{ $product->getTranslation('seo_keywords', $code) }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.meta_description') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="seo_description[{{ $code }}]" rows="8" cols="80" class="form-control"
                                                                data-name="seo_description.{{ $code }}">{{ $product->getTranslation('seo_description', $code) }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{ __('apps::dashboard.general.edit_btn') }}
                                    </button>
                                    <a href="{{ url(route('dashboard.products.index')) }}" class="btn btn-lg red">
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

            $('#jstree').jstree();

            $('#jstree').on("changed.jstree", function(e, data) {
                $('#root_category').val(data.selected);
            });

            @if (!auth()->user()->can('edit_products_category'))
                $('#jstree li').each(function() {
                    $("#jstree").jstree().disable_node(this.id);
                });
            @endif

            $('.searchKeywordsSelect').select2({
                tags: true,
            });
            $('span.select2-container').width('100%');

        });
    </script>

    @include('catalog::dashboard.products._custom_addons_js')

@endsection
