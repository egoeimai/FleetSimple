@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection



@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="row">
  <div class="col-lg-8 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Welcome Back! 🎉</h5>

          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded">
              </div>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                  <a class="dropdown-item" href="javascript:void(0);">View More</a>
                  <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                </div>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Total Clients</span>
            <h3 class="card-title mb-2">{{$totalClients}}</h3>
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
            <span class="fw-semibold d-block mb-1">Total Vehicles</span>
            <h3 class="card-title mb-2">{{$totalVehicles}}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Total Revenue -->
  <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-md-12">
          <h5 class="card-header m-0 me-2 pb-3">Emails που θα σταλούν</h5>
          <div class="card-body">
            @if($sentEmails->isEmpty())
            <p>No emails have been sent yet.</p>
            @else
            <table class="table">
              <thead>
                <tr>
                  <th>Client</th>
                  <th>Total Cost</th>
                  <th>Sent At</th>
                  <th>Cancel</th>
                  <th>Send Now</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
                @foreach($nextSevenDaysEmails as $email)
                <tr>
                  <td>{{ $email->client->email }}</td>
                  <td>€{{ number_format($email->total_cost, 2) }}</td>
                  <td>{{ $email->send_date->format('d M Y, H:i') }}</td>
                  <td><a href="{{ route('sent-emails.upcoming_show', $email->id) }}" target="_blank" class="btn btn-primary">Cancel</a></td>
                  <td>
                    <form method="POST" action="{{ route('scheduled-emails.sendNow', $email->id) }}" style="display:inline;">
                      @csrf
                      <button class="btn btn-sm btn-warning" type="submit"
                        onclick="return confirm('Να σταλεί το email τώρα;')">
                        Send Now
                      </button>
                    </form>
                  </td>
                  <td><a href="{{ route('sent-emails.upcoming_show', $email->id) }}" target="_blank" class="btn btn-primary">View</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>

          
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
  <!--/ Total Revenue -->
  <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
    <div class="row">
      <div class="col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="Credit Card" class="rounded">
              </div>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                  <a class="dropdown-item" href="javascript:void(0);">View More</a>
                  <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                </div>
              </div>
            </div>
            <span class="d-block mb-1">Έσοδα</span>
            <h3 class="card-title text-nowrap mb-2">{{$totalRevenue}} €</h3>
            <small class="text-danger fw-semibold">Τρέχων Έτος</small>
          </div>
        </div>
      </div>
      <div class="col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/cc-primary.png')}}" alt="Credit Card" class="rounded">
              </div>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                  <a class="dropdown-item" href="javascript:void(0);">View More</a>
                  <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                </div>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Transactions</span>
            <h3 class="card-title mb-2">$14,857</h3>
            <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> +28.14%</small>
          </div>
        </div>
      </div>
      <!-- </div>
    <div class="row"> -->

    </div>
  </div>
</div>
<div class="row">
  <!-- Order Statistics -->
  <div class="col-md-12 col-lg-12 col-xl-12 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Up Coming Renewals</h5>

        </div>
      </div>
      <div class="card-body">
        @if($upcomingClients->isEmpty())
        <p>No scheduled email reminders for the next 5 days.</p>
        @else
        <table class="table">
          <thead>
            <tr>
              <th>Client</th>
              <th>Email</th>
              <th>Vehicle</th>
              <th>Service</th>
              <th>Renewal Date</th>
              <th>Total Cost</th>
            </tr>
          </thead>
          <tbody>
            @foreach($upcomingClients as $client)
            @foreach($client->vehicles as $vehicle)
            @foreach($vehicle->subscriptions as $subscription)
            <tr>
              <td>{{ $client->name }}</td>
              <td>{{ $client->email }}</td>
              <td>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})</td>
              <td>{{ $subscription->service->title }}</td>
              <td>{{ \Carbon\Carbon::parse($subscription->renewal_date)->format('d M Y') }}</td>
              <td>€{{ number_format($subscription->total_cost, 2) }}</td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->

  <!-- Expense Overview -->

  <!--/ Expense Overview -->

  <!-- Transactions -->

  <!--/ Transactions -->
</div>
@endsection