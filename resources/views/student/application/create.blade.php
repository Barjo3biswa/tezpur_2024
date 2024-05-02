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
    {{-- <div class="btn-group btn-group-justified custom-step">
        <a href="#" class="btn btn-primary">Apple</a>
        <a href="#" class="btn btn-primary">Samsung</a>
        <a href="#" class="btn btn-primary">Sony</a>
    </div> --}}
{{-- <form id="application_form" action="{{route("student.application.store")}}" method="POST" class="form-horizontal" enctype="multipart/form-data"> --}}
<student-form :mode="'{{$mode['mode']}}'" :application_id="'{{$id ?? null}}'" :count="'{{$count ?? null}}'" :create_app_id="'{{$application_id ?? null}}'" :application_type="'{{$application_type ?? null}}'"></student-form>
    {{-- @include('common/application/form') --}}
{{-- </form> --}}
</div>
@endsection
@section('js')
    {{-- @include("common/application/js") --}}
@endsection