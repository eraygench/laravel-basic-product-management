@extends('errors.layout')

@php
  $error_number = 408;
@endphp

@section('title')
    İstek zaman aşımına uğradı.
@endsection

@section('description')
  @php
    $default_error_message = "Lütfen Please <a href='javascript:history.back()'>geri dönün</a>, sayfayı yenileyin ve tekrar deneyin.";

  @endphp
  {!! isset($exception)? ($exception->getMessage()?e($exception->getMessage()):$default_error_message): $default_error_message !!}
@endsection
