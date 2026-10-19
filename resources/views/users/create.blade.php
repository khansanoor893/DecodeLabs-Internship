@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<section class="page-heading">
    <div><p class="eyebrow">DIRECTORY / NEW USER</p><h1>Add a user</h1><p class="page-subtitle">Create a profile for someone in your directory.</p></div>
    <a href="{{ route('users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Back to users</a>
</section>

<div class="main-card form-card">
    <div class="main-card-header">
        <div class="card-title">
            <div class="icon"><i class="bi bi-person-plus"></i></div>
            <div>
                <h4>User Details</h4>
                <p>Enter the user's details to create their account.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Full name <span class="required-mark">*</span></label>
            <div class="input-icon">
                <i class="bi bi-person"></i>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ayesha Khan" autocomplete="name" maxlength="255" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email address <span class="required-mark">*</span></label>
            <div class="input-icon">
                <i class="bi bi-envelope"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="email" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="age">Age <span class="required-mark">*</span></label>
                <div class="input-icon">
                    <i class="bi bi-calendar2"></i>
                    <input id="age" type="number" name="age" value="{{ old('age') }}" min="0" max="130" placeholder="e.g. 24" required>
                </div>
                <small class="field-hint">Enter a whole number between 0 and 130.</small>
            </div>
            <div class="form-group status-field">
                <span class="field-label">Account status</span>
                <label class="switch-label" for="is_active">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', '1'))>
                    <span class="switch-ui" aria-hidden="true"></span>
                    <span>Active account</span>
                </label>
                <small class="field-hint">Inactive users remain saved but are marked disabled.</small>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password <span class="required-mark">*</span></label>
            <div class="input-icon">
                <i class="bi bi-lock"></i>
                <input id="password" type="password" name="password" placeholder="At least 8 characters" minlength="8" autocomplete="new-password" required>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm password <span class="required-mark">*</span></label>
            <div class="input-icon">
                <i class="bi bi-lock-fill"></i>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Enter the password again" minlength="8" autocomplete="new-password" required>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2"></i> Create User
            </button>
        </div>
    </form>
</div>

@endsection
