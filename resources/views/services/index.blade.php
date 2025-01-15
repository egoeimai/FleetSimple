@extends('layouts/contentNavbarLayout')

@section('title', 'Services')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Υπηρεσίες</span>
</h4>

<!-- Basic Bootstrap Table -->

<div class="row">
    <div class="card mb-4">

        <div class="card-body">

            <div class="">
                <a href="{{ route('services.create') }}" class="btn btn-primary">Προσθήκη Νέας Υπηρεσίας</a>

            </div>
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
                        <th>Υπηρεσία</th>
                        <th>Ενδεικτικό Κόστος</th>


                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($services as $service)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $service->title }}</td>
                        <td>${{ number_format($service->default_cost, 2) }}</td>



                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('services.edit', $service->id) }}" class="dropdown-item"><i class="bx bx-edit-alt me-1"></i>Edit</a>

                                    <form action="{{ route('services.destroy',$service->id) }}" method="Post">
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