@extends('backpack::layout', ['active_bc' => trans('backpack::crud.edit')])

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
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small>
	  </h1>
	</section>
@endsection

@section('content')
    @if ($crud->hasAccess('list'))
        <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>
    @endif

    <div class="row m-t-20">
        <div class="{{ $crud->getEditContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <form method="post"
                  action="{{ url($crud->route.'/'.$entry->getKey()) }}"
                  @if ($crud->hasUploadFields('update', $entry->getKey()))
                  enctype="multipart/form-data"
                @endif
            >
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="col-md-12">
                    @if ($crud->model->translationEnabled())
                        <div class="row m-b-10">
                            <!-- Single button -->
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                        <li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="row display-flex-wrap">
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        @if(view()->exists('vendor.backpack.crud.form_content'))
                            @include('vendor.backpack.crud.form_content', ['fields' => $fields, 'action' => 'edit'])
                        @else
                            @include('crud::form_content', ['fields' => $fields, 'action' => 'edit'])
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
