@extends('layouts.admin')

@section('title', 'Edit post | Haroon & Sons')
@section('page_heading', 'Edit post')

@section('content')
@include('admin.posts.form', ['editing' => true])
@endsection
