@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Πελάτες /</span> Δημιουργία Νέου Πελάτη
</h4>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Βασικά Στοιχεία</a></li>

        </ul>
        <div class="card mb-4">
            <h5 class="card-header">Στοιχεία Πελάτη</h5>
            <!-- Account -->
            <form id="formAccountSettings" action="{{ route('clients.create') }}" method="POST" enctype="multipart/form-data">

                <hr class="my-0">
                @if(session('status'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('status') }}
                </div>
                @endif
                <div class="card-body">

                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="old_id" class="form-label">Excel ID</label>
                            <input class="form-control" type="text" id="old_id" name="old_id" value="" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email" value="" placeholder="john.doe@example.com" />
                            @error('email')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">Ονομα</label>
                            <input class="form-control" type="text" id="firstName" name="first_name" value="" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Επιθετο</label>
                            <input class="form-control" type="text" name="lastName" id="last_name" value="" />
                        </div>



                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">Εταιρία</label>
                            <input class="form-control" type="text" name="company_name" id="company_name" value="" />
                        </div>





                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Προσθήκη</button>
                        <a href="{{ route('clients') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </div>
            </form>
            <!-- /Account -->
        </div>
    </div>
</div>
@endsection