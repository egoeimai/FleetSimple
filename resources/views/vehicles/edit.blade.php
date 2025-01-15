@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($subscriptions as $subscription)
        document.querySelectorAll('input[name="renewal_period"]').forEach(function (input) {
            input.addEventListener('change', function () {
                const customInput = document.getElementById('customDateInput-{{ $subscription->id }}');
                if (this.value === 'custom') {
                    customInput.classList.remove('d-none');
                } else {
                    customInput.classList.add('d-none');
                }
            });
        });
        @endforeach
    });
</script>

@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Client Id: {{ $vehicle->client_id }} /</span>Επεξεργασία Οχήματος
</h4>
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Στοιχεία Οχήματος</h5>
            <!-- Account -->
            <form id="formAccountSettings" action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">


                <div class="card-body">

                    @csrf
                    @method('PUT')


                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="client_id" class="form-label">Client</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ $client->id == $vehicle->client_id ? 'selected' : '' }}>
                                    id: {{ $client->id }} | Email: {{ $client->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="license_plate" class="form-label">Πινακίδα*</label>
                            <input class="form-control" type="text" id="license_plate" name="license_plate" placeholder="Enter license plate" value="{{ $vehicle->license_plate }}" required />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="brand" class="form-label">Μάρκα Οχήματος</label>
                            <input class="form-control" type="text" id="brand" name="brand" value="{{ $vehicle->brand }}" placeholder="" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="model" class="form-label">Μοντέλο</label>
                            <input class="form-control" type="text" id="model" name="model" value="{{ $vehicle->model }}" autofocus />
                        </div>



                        <div class="mb-3 col-md-6">
                            <input type="checkbox" class="form-check-input" id="enable_reminder" name="enable_reminder" value="1" {{ $vehicle->enable_reminder ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_reminder">Enable Reminder</label>
                        </div>





                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Update</button>
                        <a href="{{ route('clients') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </div>
            </form>
            <!-- /Account -->
        </div>

        <div class="card mb-4">
            <div class="card-header d-sm-flex align-items-center justify-content-between">
                <h5>Υπηρεσίες Οχήματος</h5>
                <a class="btn btn-primary me-2" href="{{ route('subscriptions.create', $vehicle->id) }}"><i class="bx bx-car me-1"></i>Προσθήκη νέας Υπηρεσίας</a>
            </div>
            <!-- Account -->
            @if ($subscriptions->isEmpty())
            <p>No vehicles associated with this client.</p>
            @else
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Total Cost</th>
                            <th>Start Date</th>
                            <th>Renewal Date</th>
                            <th>Status</th>
                            <th>Renew</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    <tbody class="table-border-bottom-0">
                        @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->service->title }}</td>
                            <td>€ {{ number_format($subscription->total_cost, 2) }}</td>
                            <td>{{ $subscription->start_date }}</td>
                            <td>{{ $subscription->renewal_date }}</td>
                            <td>{{ ucfirst($subscription->status) }}</td>
                            
                            <td>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#renewModal-{{ $subscription->id }}">
                <i class="bx bx-refresh me-1"></i> Renew
            </button>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('subscriptions.edit', $subscription->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>

                                        <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST">
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



                    </tbody>
                </table>
                
                
                @foreach ($subscriptions as $subscription)
<div class="modal fade" id="renewModal-{{ $subscription->id }}" tabindex="-1" aria-labelledby="renewModalLabel-{{ $subscription->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renewModalLabel-{{ $subscription->id }}">Renew Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Select a renewal option for the subscription:</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="renewal_period" id="3months-{{ $subscription->id }}" value="3">
                        <label class="form-check-label" for="3months-{{ $subscription->id }}">3 Months</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="renewal_period" id="4months-{{ $subscription->id }}" value="4">
                        <label class="form-check-label" for="4months-{{ $subscription->id }}">4 Months</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="renewal_period" id="6months-{{ $subscription->id }}" value="6">
                        <label class="form-check-label" for="6months-{{ $subscription->id }}">6 Months</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="renewal_period" id="12months-{{ $subscription->id }}" value="12">
                        <label class="form-check-label" for="12months-{{ $subscription->id }}">12 Months</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="renewal_period" id="custom-{{ $subscription->id }}" value="custom">
                        <label class="form-check-label" for="custom-{{ $subscription->id }}">Custom Date</label>
                    </div>
                    <div id="customDateInput-{{ $subscription->id }}" class="mt-3 d-none">
                        <label for="custom_date-{{ $subscription->id }}" class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="custom_date-{{ $subscription->id }}" name="custom_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Renew</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

            </div>
            @endif
            <!-- /Account -->
        </div>
    </div>


</div>
@endsection