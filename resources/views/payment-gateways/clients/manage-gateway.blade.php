@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
    'type'    => 'div',
    'class' => 'row',
    'content' => [

    ]
];
@endphp

@section('header')
    <div class="container-fluid">
        <h2 class="text-light">
            <span class="text-capitalize">Manage {{ $gateway_name }}</span>
            <small id="datatable_info_stack">On your account.</small>
        </h2>
    </div>
@endsection

@section('content')
    {{-- GATEWAY CONFIG FIELDS --}}
    <div class="row" style="padding-top: 5%;">
        <div class="col-lg-12">
            <div class="form col-md-12">
                <div class="row space-between">
                    <form class="col-md-6" action="/access/payment-gateways/{!! $entry->id !!}/manage/enable" method="post">
                        {!! csrf_field() !!}
                        <div class="card padding-10 col-md-12">
                            <div class="card-header">
                                Configuration
                            </div>

                            <div class="card-body backpack-profile-form bold-labels col-md-12">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Status</label><span> - {!! $gate_status->value !!}</span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        @php
                                            $assignable = 'No';
                                            $has_savable = (array_key_exists('savable', $gate_status->misc));
                                            $is_savable = ($has_savable && $gate_status->misc['savable']);
                                            $has_assignable = (array_key_exists('assignable', $gate_status->misc));
                                            if($has_assignable)
                                            {
                                                 if($gate_status->misc['assignable'])
                                                 {
                                                    $assignable = 'Yes';
                                                 }
                                            }
                                        @endphp
                                        <label>Can Be Assigned to Your Shops?</label><span> - {!! $assignable !!}</span>

                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label>Commission</label><span> - {!! $commission->misc['percent'] * 100 !!}% per transaction</span>
                                    </div>
                                </div>
                                @foreach($form_fields as $field)
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="card" style="border: transparent">
                                                <label class="required">{{ $field->value }}</label>
                                                @if($field->misc['type'] == 'html')
                                                    <p>{!! $field->misc['text'] !!}</p>
                                                @elseif($field->misc['type'] == 'text')
                                                    <input class="form-control" type="text" name="{!! $field->misc['slug'] !!}" @if(!empty($enabled)) value="{!! $enabled['misc'][$field->misc['slug']] !!}"@endif @if($field->misc['required']) required @endif placeholder="{!! $field->value !!}"/>
                                                @elseif($field->misc['type'] == 'select')
                                                    <select class="form-control" name="{!! $field->misc['slug'] !!}" value="{!! empty($enabled) ? '' : $enabled['misc'][$field->misc['slug']] !!}" @if($field->misc['required']) required @endif>
                                                        @foreach($field->misc['options'] as $val => $text)
                                                            <option value="{!! $val !!}" @if((!empty($enabled)) && ($enabled['misc'][$field->misc['slug']] == $val)) selected @endif>{!! $text !!}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            {{-- <input required class="form-control" type="text" name="" value="{{ old($field) ? old($field) : $user->$field }}"> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($is_savable)
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.save') }}</button>
                                </div>
                            @endif
                        </div>
                    </form>

                    <div class="row col-md-6 col-sm-6 col-sm-auto">
                        <form class="col-md-11 col-sm-6" action="/access/payment-gateways/{!! $entry->id !!}/manage/assign" method="post">
                            {!! csrf_field() !!}
                            <div class="card padding-10 col-md-12 col-sm-12">
                                <div class="card-header">
                                    Assign to your Shops
                                </div>

                                <div class="card-body backpack-profile-form bold-labels col-md-12">
                                    <div class="row">
                                        @foreach ($client->shops()->get() as $shop)
                                            @php
                                                $assigned_provider = $shop->shop_assigned_payment_providers()
                                                                            ->whereProviderUuid($entry->id)
                                                                            ->whereActive(1)
                                                                            ->first();
                                            @endphp

                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <label class="font-weight-normal">
                                                        <input name="assigned[{!! $shop->id !!}]" type="checkbox" value="1" @if(!is_null($assigned_provider)) checked @endif> {{ $shop->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.save') }}</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <style>
        @media screen {
            .margin-auto {
                margin: auto;
            }
            .space-between {
                justify-content: space-between;
            }

            .space-around {
                justify-content: space-evenly;
            }

            .active-cyan-2 input.form-control[type=text] {
                border-top: none;
                border-left: none;
                border-right: none;
                background: transparent;
            }

            .active-cyan-2 input.form-control[type=text]::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
                color: darkslategray;
                opacity: 1; /* Firefox */
            }

            .active-cyan-2 input.form-control[type=text]:focus:not([readonly]) {
                border-bottom: 1px solid #42ba96;
                box-shadow: 0 1px 0 0 #42ba96;
            }

            .active-cyan input.form-control[type=text] {
                border-bottom: 1px solid #4dd0e1;
                box-shadow: 0 1px 0 0 #4dd0e1;
            }
        }

        @media screen and (max-width: 999px) {
            .col-sm-auto {
                margin:auto;
            }
        }
    </style>
@endsection
