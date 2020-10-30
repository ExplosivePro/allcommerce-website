@extends('errors.layout')

@php
	$error_number = 501;
@endphp

@section('before_styles')
	<style>
		#app {
			height: 100%;
			padding-left: 0;
			padding-right: 0;
		}

		.inner-container {
			justify-content: center;
			align-items: center;
			display: flex;
			flex-flow: column;
			height: 85%;
		}
	</style>
@endsection

@section('title')
	It's not you, it's me.
@endsection

@section('description')
	@php
	  $default_error_message = "An internal server error has occurred. If the error persists please contact the development team.";
	@endphp
	{!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
@endsection
