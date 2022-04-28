@extends('errors.layout')

@php
	$error_number = 500;
@endphp

@section('title')
	Sunucu kaynaklı hata.
@endsection

@section('description')
	@php
	  $default_error_message = "Dahili bir sunucu hatası oluştu. Hata devam ederse, lütfen geliştirme ekibiyle iletişime geçin.";
	@endphp
	{!! isset($exception)? ($exception->getMessage()?e($exception->getMessage()):$default_error_message): $default_error_message !!}
@endsection
