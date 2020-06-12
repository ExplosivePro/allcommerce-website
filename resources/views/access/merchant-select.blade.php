@extends('layouts.app')

@section('extra-header-stuff')
    <style>
        @media screen {
            .container {
                width: 100%;
                height: 100%;
            }

            .inner-container {
                margin: 0;
                display: flex;
            }

        }

        @media screen and (max-width: 999px) {
            .inner-container {
                flex-flow: column;
            }


        }

        @media screen and (min-width: 1000px) {
            .inner-container {
                flex-flow: row;
            }


        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="inner-container">
           <div class="logo-area-section"></div>
           <div class="main-verbiage-section">
               <div class="inner-verbiage">
                   <h1> Select an Account to Continue</h1>
               </div>
           </div>
           <div class="merchant-select-section">
                <select-post-grid
                    :options="{{ json_encode($merchant_select) }}"
                    url="/access/dashboard"
                ></select-post-grid>
           </div>
        </div>
    </div>
@endsection
