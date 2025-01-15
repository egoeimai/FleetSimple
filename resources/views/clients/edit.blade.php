@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Πελάτες /</span> Καρτέλα Πελάτη
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
            <li class="nav-item"><a class="nav-link" href="{{ route('clients.revenue', $client->id) }}"><i class="bx bx-user me-1"></i>Οικονομικά</a></li>

            
        </ul>
        <div class="row">
            <div class="col-md-8 order-1">
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
                            <input class="form-control" type="text" id="old_id" name="old_id" value="{{ $client->old_id }}" readonly />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email" value="{{ $client->email }}" placeholder="john.doe@example.com" />
                            @error('email')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">Ονομα</label>
                            <input class="form-control" type="text" id="firstName" name="first_name" value="{{ $client->first_name }}" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Επιθετο</label>
                            <input class="form-control" type="text" name="lastName" id="last_name" value="{{ $client->last_name }}" />
                        </div>



                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">Εταιρία</label>
                            <input class="form-control" type="text" name="company_name" id="company_name" value="{{ $client->company_name }}" />
                        </div>





                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Αποθήκευση</button>
                        <a href="{{ route('clients') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </div>
            </form>
            <!-- /Account -->
        </div>
        </div>
        
        <div class="col-lg-4 col-md-4 order-2">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded">
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Cars</span>
            <h3 class="card-title mb-2">{{ $vehicleCount }}</h3>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/wallet-info.png')}}" alt="Credit Card" class="rounded">
              </div>
 
            </div>
            <span>Services</span>
            <h3 class="card-title text-nowrap mb-1">{{ $serviceCount }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
        
        </div>

        <div class="card mb-4">
        <div class="card-header">
            <h5>Upcoming Renewals (Next 30 Days)</h5>
        </div>
        <div class="card-body">
            @if($upcomingRenewals->isEmpty())
                <p>No upcoming renewals in the next 30 days.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Service</th>
                                <th>Renewal Date</th>
                                <th>Days Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcomingRenewals as $subscription)
                                <tr>
                                    <td>
                                        {{ $subscription->vehicle->brand }} {{ $subscription->vehicle->model }}
                                        ({{ $subscription->vehicle->license_plate }})
                                    </td>
                                    <td>{{ $subscription->service->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->renewal_date)->toFormattedDateString() }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->renewal_date)->diffInDays() }} days</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
        
        <div class="card mb-4">
            <div class="card-header d-sm-flex align-items-center justify-content-between">
                <h5>Οχήματα</h5>
                <a class="btn btn-primary me-2" href="{{ route('vehicles.create', $client->id) }}"><i class="bx bx-car me-1"></i>Προσθήκη Οχήματος</a>
            </div>
            <!-- Account -->
            @if ($client->vehicles->isEmpty())
            <p>No vehicles associated with this client.</p>
            @else
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Πινακίδα</th>
                            <th>Μάρκα</th>
                            <th>Μοντέλο</th>
                            <th>Enable Reminder</th>
                            <th>Υπηρεσίες</th>


                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    <tbody class="table-border-bottom-0">
                        @foreach ($client->vehicles as $vehicle)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $vehicle->license_plate }}</td>
                            <td>{{ $vehicle->brand ?? 'N/A' }}</td>
                            <td>{{ $vehicle->model ?? 'N/A' }}</td>
                            <td>{{ $vehicle->enable_reminder ? 'Yes' : 'No' }}</td>
                            <td>
                            @if ($vehicle->subscriptions->isEmpty())
                                <span class="badge bg-light text-dark me-1">No services</span>
                            @else
                                @foreach ($vehicle->subscriptions as $subscription)
                                @if($subscription->status == 'active')
                                <span class="badge bg-light text-dark me-1">{{ $subscription->service->title }} ({{ number_format($subscription->total_cost, 2) }} €),</span>
                                @endif
                                @endforeach
                            @endif    
             
                            
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('vehicles.edit', $vehicle->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>

                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST">
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
            </div>
            @endif
            <!-- /Account -->
        </div>
    </div>
</div>
@endsection