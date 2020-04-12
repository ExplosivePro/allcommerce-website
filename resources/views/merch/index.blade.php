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

            .stuff {
                width: 100%;
            }

            .stuff .col-md-8 {
                margin: 1em
            }
        }

        @media screen and (max-width: 999px) {
            .inner-container {
                flex-flow: column;
            }

            .side-nav-bar {
                display: none;
            }

            .stuff {
                width: 100%;
            }
        }

        @media screen and (min-width: 1000px) {
            .inner-container {
                flex-flow: row;
            }

            .side-nav-bar {
                background-color: #2D2D2D;
                width: 20%;
            }

            .stuff {
                width: 80%;
            }
        }
    </style>

    @include('includes.content-grid-css')
@endsection

@section('content')
    <div class="container">
        <div class="inner-container">
            <nav class="side-nav-bar">
                <allcommerce-sidebar></allcommerce-sidebar>
            </nav>
            <div class="row justify-content-center stuff">
                <div class="col-md-8">
                    <div class="card">
                        @include('includes.content-grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-footer-stuff')

@endsection