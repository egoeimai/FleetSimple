@extends('layouts/contentNavbarLayout')

@section('title', 'Προσθήκη Υπηρεσίας')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Όχημα {{ $vehicle->license_plate }} /</span> Προσθήκη Συνδρομής
</h4>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Στοιχεία Συνδρομής</h5>
            <!-- Account -->
            <form id="formAccountSettings" action="{{ route('subscriptions.store') }}" method="POST" enctype="multipart/form-data">


                <div class="card-body">

                    @csrf
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="service_id" class="form-label">Service</label>
                            <select name="service_id" id="service_id" class="form-control" required>
                                @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="total_cost" class="form-label">Total Cost</label>
                            <input type="number" name="total_cost" id="total_cost" class="form-control" step="0.01" min="0" required>
                            @error('total_cost')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                            @error('start_date')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="renewal_date" class="form-label">Renewal Date</label>
                            <input type="date" name="renewal_date" id="renewal_date" class="form-control" required>
                            @error('renewal_date')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="renewed">Renewed</option>
                            </select>
                        </div>








                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Προσθήκη</button>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </div>
            </form>
            <!-- /Account -->
        </div>
    </div>
</div>
@endsection