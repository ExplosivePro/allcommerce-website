@extends('layouts.checkouts.default-checkout')

@section('before_styles')
    <style>
        @media screen {
            .checkout-content {
                height: 100%;
                width: 100%;
            }

            .container-fluid {
                max-width: 1200px;
                width: 100%;
            }

            .container-fluid > div {
                margin-right: auto;
                margin-left: auto;
            }

            .inner-checkout-content {
                height: 100%;
                display: flex;
                flex-flow: column;
            }

            .row {
                width: 100%;
            }

            .header-section {
                height: 15%;
            }

            .inner-header-section {
                height: 100%;
            }



            .footer-section {
                height: 10%;
            }

            .inner-footer-section {
                height: 100%;
                display: flex;
                flex-flow: column;
                justify-content: center;
                text-align: center;
            }

            .copyright-segment {
                width: 100%;
            }
        }

        @media screen and (max-width: 999px) {
            .container-fluid > div {
                padding-right: 2.5%;
                padding-left: 2.5%;
            }

            .inner-header-section {
                padding: 7.5% 0 2.5%;
            }

            .inner-header-section p {
                font-size: 2em;
            }

            .copyright {
                font-size: 65%;
            }
        }

        @media screen and (min-width: 1000px) {
            .container-fluid > div {
                padding-right: 1%;
                padding-left: 1%;
            }

            .inner-header-section {
                padding: 5% 0 1%;
            }

            .inner-header-section p {
                font-size: 2.5em;
            }

            .copyright {
                font-size: 85%;
            }
        }
    </style>
@endsection

@section('before_scripts')

@endsection

@section('content')
    <div class="checkout-content container-fluid">
        <div class="inner-checkout-content">
            <div class="row header-section">
                <div class="inner-header-section">
                    <p>{!! $shop_name !!}</p>
                </div>
            </div>
            <default-checkout-experience
                :items="{{ json_encode($items) }}"
                :shipping-methods="{{ json_encode($shipping_methods) }}"
                :gateways="{{ json_encode($payment_gateways) }}"
                shop-id="{!! $shop_uuid !!}"
                checkout-type="{!! $checkout_type !!}"
                checkout-id="{!! $checkout_id !!}"
                api-url="{!! env('APP_URL') !!}"
                :dev-mode="devMode"
            ></default-checkout-experience>
            <div class="row footer-section">
                <div class="inner-footer-section">
                    <div class="copyright-segment">
                        <p class="copyright"><i class="fal fa-copyright"></i> 2020. All rights reserved. Powered by AllCommerce. A Cape & Bay Solution</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')

@endsection
