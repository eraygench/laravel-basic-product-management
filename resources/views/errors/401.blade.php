@extends('errors.layout')

@php
  $error_number = 401;
@endphp

@section('title')
    Yetkisiz işlem.
@endsection

@section('description')
  @php
    $default_error_message = "Lütfen <a href='javascript:history.back()'>geri dönün</a> veya <a href='".(Route::current()->parameter('company_slug') ? route('company', ['company_slug' => Route::current()->parameter('company_slug')]) :url(''))."'>ana sayfaya</a> dönün.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?e($exception->getMessage()):$default_error_message): $default_error_message !!}
@endsection
