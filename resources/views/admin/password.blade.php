@extends('layouts.admin')

@section('title', 'Change Password')
@section('page_heading', 'Change Password')

@section('content')
<section class="admin-panel form-panel">
    <h2>Update Administrator Password</h2>

    @if($errors->any())
        <div class="form-errors">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-field">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>

        <div class="form-field">
            <label>New Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-field">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button class="admin-button" type="submit">Change Password</button>
    </form>
</section>
@endsection