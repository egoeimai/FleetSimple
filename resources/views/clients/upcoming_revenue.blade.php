@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection
@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Πελάτες /</span> Καρτέλα Πελάτη/ Revenue for Client {{ $client->id }} - {{ $year }}
</h4>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link " href="{{ route('clients.edit', $client->id) }}"><i class="bx bx-user me-1"></i> Βασικά Στοιχεία</a></li>
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>Οικονομικά</a></li>

            
        </ul>
	
<div class="card mt-4">
        <div class="card-header">
            <h5>Change Year</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clients.revenue', ['clientId' => $client->id]) }}">
                <div class="mb-3">
                    <label for="year" class="form-label">Select Year</label>
					<select id="year" name="year" class="form-select">
    @foreach ($years as $y)
        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
    @endforeach
</select>
                </div>
                <button type="submit" class="btn btn-primary">View Revenue</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Summary</h5>
        </div>
        <div class="card-body">
            <p><strong>Client Id:</strong> {{ $client->Id }}</p>
            <p><strong>Total Revenue for {{ $year }}:</strong> € {{ number_format($totalRevenue, 2) }}</p>
            <p><strong>Total Subscriptions:</strong> {{ $subscriptionsForYear->count() }}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Subscriptions</h5>
        </div>
        <div class="card-body">
            @if($subscriptionsForYear->isEmpty())
                <p>No subscriptions found for the year {{ $year }}.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Service</th>
                                <th>Renewal Date</th>
                                <th>Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptionsForYear as $subscription)
                                <tr>
                                    <td>
                                        {{ $subscription->vehicle->brand }} {{ $subscription->vehicle->model }}
                                        ({{ $subscription->vehicle->license_plate }})
                                    </td>
                                    <td>{{ $subscription->service->title }}</td>
                                    <td>{{ $subscription->renewal_date }}</td>
                                    <td>€ {{ number_format($subscription->total_cost, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
	</div>


@endsection
