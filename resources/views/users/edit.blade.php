@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<section class="page-heading">
    <div><p class="eyebrow">DIRECTORY / EDIT USER</p><h1>Edit user</h1><p class="page-subtitle">Update the details for {{ $user->name }}.</p></div>
    <a href="{{ route('users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Back to users</a>
</section>

<div class="main-card form-card">
    <div class="main-card-header">
        <div class="card-title">
            <div class="icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h4>User Details</h4>
                <p>Changes are saved to this user's profile.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Full name <span class="required-mark">*</span></label>
            <div class="input-icon">
                <i class="bi bi-person"></i>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" maxlength="255" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email address <span class="required-mark">*</span></label>
            <div class="input-icon">
                <i class="bi bi-envelope"></i>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="age">Age <span class="required-mark">*</span></label>
                <div class="input-icon">
                    <i class="bi bi-calendar2"></i>
                    <input id="age" type="number" name="age" value="{{ old('age', $user->age) }}" min="0" max="130" required>
                </div>
                <small class="field-hint">Enter a whole number between 0 and 130.</small>
            </div>
            <div class="form-group status-field">
                <span class="field-label">Account status</span>
                <label class="switch-label" for="is_active">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', (string) (int) $user->is_active))>
                    <span class="switch-ui" aria-hidden="true"></span>
                    <span>Active account</span>
                </label>
                <small class="field-hint">Inactive users remain saved but are marked disabled.</small>
            </div>
        </div>

        <div class="form-group">
            <label for="password">New password <span class="optional-mark">Optional</span></label>
            <div class="input-icon">
                <i class="bi bi-lock"></i>
                <input id="password" type="password" name="password" placeholder="Leave blank to keep current password" minlength="8" autocomplete="new-password">
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm new password</label>
            <div class="input-icon">
                <i class="bi bi-lock-fill"></i>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Enter the new password again" minlength="8" autocomplete="new-password">
            </div>
        </div>

        <div class="form-hint">
            <i class="bi bi-info-circle"></i> Leave password fields blank if you don't want to change it.
        </div>

        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2"></i> Update User
            </button>
        </div>
    </form>
</div>

@endsection
