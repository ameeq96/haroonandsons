@extends('layouts.admin')

@section('title', 'Create post | Haroon & Sons')
@section('page_heading', 'Create post')

@section('content')
@include('admin.posts.form', ['editing' => false])
@endsection
