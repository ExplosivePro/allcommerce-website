@extends('backpack::layout', ['active_bc' => trans('backpack::crud.add')])

@section('before_styles')
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
    </style>
@endsection

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>
	  </h1>
	</section>
@endsection

@section('content')
@if ($crud->hasAccess('list'))
	<a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>
@endif

<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  <div class="col-md-12">

		    <div class="row display-flex-wrap">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif
		    </div><!-- /.box-body -->
		    <div class="">

                @include('crud::inc.form_save_buttons')

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection
