@extends('layouts/contentNavbarLayout')

@section('title', 'Αθλητές')
@section('content')
<h1>Edit User</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="form-group">
        <label for="role">Role</label>
        <select name="role" class="form-control" required>
            <option value="">Select a role</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
</form>
@endsection