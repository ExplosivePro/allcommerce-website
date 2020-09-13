@extends('backpack::layout', ['active_bc' => trans('backpack::crud.list')])

@section('before_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/base/backpack.base.css') }}?v=3">
    @if (config('backpack.base.overlays') && count(config('backpack.base.overlays')))
        @foreach (config('backpack.base.overlays') as $overlay)
            <link rel="stylesheet" href="{{ asset($overlay) }}">
        @endforeach
    @endif

    <style>
        @media screen {
            .content-header {
                margin-bottom: 2.5%;
            }

            .content-header h1 {
                display:flex;
                flex-flow: row;
            }

            .content .row .row.m-b-10 {
                margin-bottom: 2.5%;
            }

            .content .row .col-xs-6 {
                width: 50%;
            }

            #crudTable_wrapper {
                width: 100%;
            }

            #crudTable_wrapper .row .col-sm-12 {
                padding: 0 !important;
            }

            #crudTable_wrapper .col-sm-6.col-md-4 #crudTable_length .form-control.input-sm {
                margin-right: 5%;
            }
        }

        @media screen and (max-width: 999px) {
            .content-header .text-capitalize {
                font-size: 80%;
            }

            #datatable_info_stack {
                display: inline-block;
                padding-left: 2.5%;
                margin-top: 2%;
                width: 40%;
            }

            .dataTables_info {
                font-size: 45%;
            }

            .content .row .col-xs-6 .hidden-print.with-border {
                margin-left: 5%;
            }

            .content .row .col-xs-6 #datatable_search_stack {
                margin-right: 5%;
            }

            #crudTable_wrapper .row {
                width: 100%;
                margin: 0 !important;
            }

            #crudTable_wrapper .row.hidden {
                width: unset;
            }

            @media screen and (max-width: 5745px) {
                .pagination {
                    margin-top: 10%;
                    flex-flow: row;
                    justify-content: center;
                    align-items: center;
                }

                .pagination li {
                    display: inline;
                    margin: 0 0.5em;
                    text-align: center;
                }

                #crudTable_wrapper .col-sm-6.col-md-4.hidden-print .dataTables_paginate .active a{
                    z-index: 3;
                    color: #fff;
                    cursor: default;
                    background-color: #337ab7;
                    border: none;
                    border-radius: 5px;
                    padding: 0.1em 0.5em;
                }
            }

            @media screen and (min-width: 576px) {
                #crudTable_wrapper .col-sm-6.col-md-4 {
                    padding-left: 0;
                    padding-right: 0;
                    max-width: 50%;
                    min-width: 50%;
                }

                #crudTable_wrapper .col-sm-6.col-md-4 #crudTable_length {
                    float: left;
                    margin-left: 2%;
                }

                #crudTable_wrapper .col-sm-2.col-md-4.text-center{
                    display:none;
                }

                .pagination {
                    flex-flow: row;
                    justify-content: flex-end;
                    align-items: center;
                }

                .pagination li {
                    display: inline;
                    margin: 0 0.5em;
                    text-align: center;
                }

                #crudTable_wrapper .col-sm-6.col-md-4.hidden-print .dataTables_paginate .active a{
                    z-index: 3;
                    color: #fff;
                    cursor: default;
                    background-color: #337ab7;
                    border: none;
                    border-radius: 5px;
                    padding: 0.1em 0.5em;
                }
            }
        }

        @media screen and (min-width: 1000px) {
            .content-header .text-capitalize {
                font-size: 85%;
            }

            #datatable_info_stack {
                display: inline-block;
                padding-left: 2.5%;
                margin-top: 1%;
                width: 50%;
            }

            .dataTables_info {
                font-size: 55%;
            }

            .content .row .col-xs-6 .hidden-print.with-border {
                margin-left: 2.5%;
            }

            .content .row .col-xs-6 #datatable_search_stack {
                margin-right: 2.5%;
            }

            #crudTable_wrapper .row .col-sm-12 {
                padding: 0 !important;
            }

            #crudTable_wrapper .row {
                width: 100%;
                margin: 0 !important;
            }

            #crudTable_wrapper .col-sm-6.col-md-4 {
                padding-left: 0;
                width: 50%;
                max-width: 50%;
                min-width: 50%;
            }

            #crudTable_wrapper .col-sm-6.col-md-4 #crudTable_length {
                float: left;
                margin-left: 2%;
            }

            #crudTable_wrapper .col-sm-6.col-md-4 {
                padding-left: 0;
                padding-right: 0;
            }

            #crudTable_wrapper .col-sm-6.col-md-4 #crudTable_length {
                float: left;
                margin-left: 2%;
            }

            @if (!$crud->exportButtons())
            #crudTable_wrapper .col-sm-2.col-md-4.text-center{
                display:none;
            }
            @endif

            .pagination {
                flex-flow: row;
                justify-content: flex-end;
                align-items: center;
            }

            .pagination li {
                display: inline;
                margin: 0 0.5em;
                text-align: center;
            }

            #crudTable_wrapper .col-sm-6.col-md-4.hidden-print .dataTables_paginate .active a{
                z-index: 3;
                color: #fff;
                cursor: default;
                background-color: #337ab7;
                border: none;
                border-radius: 5px;
                padding: 0.1em 0.5em;
            }
        }
    </style>
@endsection

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="row">
      <!-- THE ACTUAL CONTENT -->
      <div class="{{ $crud->getListContentClass() }}">
      <div class="">

        <div class="row m-b-10">
          <div class="col-xs-6">
            @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
            <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

              @include('crud::inc.button_stack', ['stack' => 'top'])

            </div>
            @endif
          </div>
          <div class="col-xs-6">
              <div id="datatable_search_stack" class="pull-right"></div>
          </div>
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <div class="overflow-hidden">
            <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
                <thead>
                <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-visible="{{ var_export($column['visibleInTable'] ?? true) }}"
                    data-visible-in-modal="{{ var_export($column['visibleInModal'] ?? true) }}"
                    data-visible-in-export="{{ var_export($column['visibleInExport'] ?? true) }}"
                    >
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th>{!! $column['label'] !!}</th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
                </tr>
                </tfoot>
            </table>
            @if ($crud->buttons->where('stack', 'bottom')->count())
                <div id="bottom_buttons" class="hidden-print">
                @include('crud::inc.button_stack', ['stack' => 'bottom'])

                <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
              </div>
            @endif
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
	@include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
