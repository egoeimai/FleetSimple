@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Client Id: {{ $client->old_id }} /</span> Καταχώρηση Νέου Οχήματος
</h4>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Στοιχεία Οχήματος</h5>
            <!-- Account -->
            <form id="formAccountSettings" action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">

                <hr class="my-0">
                @if(session('status'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('status') }}
                </div>
                @endif
                <div class="card-body">

                    @csrf
                    <input type="hidden" name="client_id" value="{{ $client->id }}">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="license_plate" class="form-label">Πινακίδα*</label>
                            <input class="form-control" type="text" id="license_plate" name="license_plate" placeholder="Enter license plate" value="" required />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="brand" class="form-label">Μάρκα Οχήματος</label>
                            <input class="form-control" type="text" id="brand" name="brand" value="" placeholder="" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="model" class="form-label">Μοντέλο</label>
                            <input class="form-control" type="text" id="model" name="model" value="" autofocus />
                        </div>



                        <div class="mb-3 col-md-6">
                            <input type="checkbox" class="form-check-input" id="enable_reminder" name="enable_reminder" value="1" checked>
                            <label class="form-check-label" for="enable_reminder">Enable Reminder</label>
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