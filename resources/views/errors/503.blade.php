@extends('errors.layout')

@php
  $error_number = 503;
@endphp

@section('title')
    Sunucu kaynaklı hata.
@endsection

@section('description')
  @php
    $default_error_message = "Sunucu aşırı yüklenmiş veya bakım nedeniyle kapalı. Lütfen daha sonra tekrar deneyiniz.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?e($exception->getMessage()):$default_error_message): $default_error_message !!}
@endsection
