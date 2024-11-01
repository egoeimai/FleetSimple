@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')

<div class="row">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="card">

        <div class="card-header d-sm-flex align-items-center justify-content-between">
            <h2>Χρήστες</h2> <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Όνομα</th>
                        <th>Email</th>

                        <th>Role</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    <hr class="my-5">
</div>

@endsection