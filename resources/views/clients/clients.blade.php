@extends('layouts/contentNavbarLayout')

@section('title', 'Clients')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Πελάτες /</span> Σύνολο {{ $clientsCount }} Πελατών
</h4>

<!-- Basic Bootstrap Table -->

<div class="row">
    <div class="card mb-4">

        <div class="card-body">

            <div class="">
                <a href="{{ route('clients.create') }}" class="btn btn-primary">Προσθήκη Νέου Πελάτη</a>

            </div>
        </div>
        <hr class="m-0" />




    </div>
</div>
<div class="row">
    <div class="card mb-4 col-md-12">

        <div class="card-body">

            <form action="{{ route('clients-filter') }}" method="get">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" class="form-control mb-3" placeholder="search" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="form-control mb-3" value="Search">
                    </div>
                </div>
            </form>

        </div>
        <hr class="m-0" />




    </div>






</div>

<div class="row">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="card">


        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Όνομα</th>
                        <th>email</th>

                        <th>Οχμήματα</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->old_id }}</td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $client->first_name }} {{ $client->last_name }}</strong></td>
                        <td>{{ $client->email }}</td>

                        <td> {{ $client->vehicles->pluck('license_plate')->join(', ') }}</td>
                        <td>

                            <a class="btn btn-success" href="{{ route('clients.edit',$client->id) }}"><i class="bx bx-edit-alt me-1"></i>Επεξεργασία</a>

                        </td>


                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                <div class="dropdown-menu">
                                    <form action="{{ route('clients.destroy',$client->id) }}" method="Post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button>

                                    </form>


                                </div>
                            </div>
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

<!--/ Responsive Table -->
@endsection