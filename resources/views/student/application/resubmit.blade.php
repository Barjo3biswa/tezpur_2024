@extends('student.layouts.form')
@section("content")
@section('css')
<style>
    .custom-step a {
        margin-left: 2px;
    }
    .margin-label{
        padding-top:6px;
    }
</style>
<link rel="stylesheet" href="{{asset("css/tab_style.css")}}">
@endsection
<div class="container" id="page-content">
  
<resubmit-form  :application="{{$application ?? null}}" ></resubmit-form>
    
</div>
@endsection
@section('js')

@endsection