@extends('backpack::layout', ['active_bc' => trans('backpack::crud.add')])

@section('before_styles')
    <style>
        @media screen {
            #app {
                width: 100%;
            }
            .content-header {
                margin-bottom: 2.5%;
            }

            .content-header h1 {
                display:flex;
                flex-flow: row;
            }

            .col-md-8.col-md-offset-2 {
                width: 100%;
                flex: 100%;
                max-width: 100%;
            }

            .c-wrapper:not(.c-wrapper-fluid) .c-body {
                overflow-y: unset
            }

            .form-group {
                margin-top: 1rem;
            }
        }

        @media screen and (max-width: 999px) {
            .content-header .text-capitalize {
                font-size: 80%;
            }

            .content-header h1 small {
                font-size: 40%;
                padding-left: 2.5%;
                margin-top: 2%;
                width: 40%;
            }

        }

        @media screen and (min-width: 1000px) {
            .mobile-only {
                display: none;
            }

            .content-header .text-capitalize {
                font-size: 85%;
            }

            .content-header h1 {
                width: 100%;
            }
            .content-header h1 small {
                padding-left: 2.5%;
                margin-top: 2%;
                font-size: 55%;
                width: 40%;
            }

            .col-md-8.col-md-offset-2 form {
                margin: 2.5% 15% 0;
            }
        }
    </style>
@endsection

@section('header')
    <section class="container-fluid">
        <h1>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small style="font-size: 55%;">{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

            @if ($crud->hasAccess('list'))
                <br class="mobile-only" />
                <small><a href="{{ url($crud->route) }}" class="hidden-print d-print-none font-sm"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h1>
    </section>
@endsection

@section('content')
    <div class="row">
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
                  <!-- load the view from the application if it exists, otherwise load the one in the package -->
                  @if(view()->exists('vendor.backpack.crud.form_content'))
                      @include('vendor.backpack.crud.form_content', [ 'fields' => $fields, 'action' => 'create' ])
                  @else
                      @include('crud::form_content', [ 'fields' => $fields, 'action' => 'create' ])
                  @endif

                  @include('crud::inc.form_save_buttons')
              </form>
        </div>
    </div>
@endsection
