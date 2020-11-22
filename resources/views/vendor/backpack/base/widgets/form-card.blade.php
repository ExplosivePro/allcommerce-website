@php
    // preserve backwards compatibility with Widgets in Backpack 4.0
    $widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
@endphp

@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
<div class="{{ $widget['class'] ?? 'card' }}">
    @if (isset($widget['content']))
        @if (isset($widget['content']['header']))
            <div class="card-header">{!! $widget['content']['header'] !!}</div>
        @endif
            <div class="card-body">
                <form action="{!! $widget['action'] !!}" method="post">
                    @csrf
                    @foreach($widget['content']['body'] as $form_group => $fields)
                        <div class="card-header bg-yellow col-md-3" style="margin-top: 1.75em;">{!! $form_group !!}</div>
                        <div class="form-group bg-light col-md-12">
                        @foreach($fields as $field_id => $field)
                            <div element="div" style="padding: 1em 0;" class="form-group  {!! (isset($field['required']) && ($field['required'])) ? 'required' : '' !!}">
                                <label for="{!! $field_id !!}" class="">{!! $field['label'] !!}</label>
                                <input class="{!! $field['class'] ?? 'form-control' !!}" id="{!! $field_id !!}" type="{{ $field['type'] }}" name="{{ $field['name'] }}" placeholder="{!! $field['placeholder'] !!}" @if(isset($field['required']) && ($field['required'])) required @endif
                                value="{{ old($field_id) }}">
                                <span class="help-block">{!! $field['hint'] !!}</span>
                            </div>
                        @endforeach
                        </div>
                    @endforeach

                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i> Submit</button>
                        <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-ban"></i> Reset</button>
                    </div>
                </form>
            </div>
    @endif
</div>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')

@section('before_styles')
<style>
    .required label::after {
        content: ' *';
        color: #ff0000;
    }
</style>
@endsection
