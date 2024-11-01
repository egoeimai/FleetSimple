@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Αθλούμενος / </span> Πληρωμές
</h4>
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link" href="{{url('pages/clients-edit', $client->id)}}"><i class="bx bx-user me-1"></i> Καρτέλα</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/entrances', $client->id)}}"><i class="bx bx-bell me-1"></i> Παρουσίες</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-link-alt me-1"></i> Πληρωμές</a></li>

    </ul>
    <div class="row">
      <div class="col-md-8 col-12 mb-md-0 mb-4">
        <!-- Basic Bootstrap Table -->

        <div class="card">
          <h5 class="card-header">Table Basic</h5>
          <div class="table-responsive text-nowrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Μηνας</th>
                  <th>Ποσο</th>
                  <th>Ημερομηνια Πληρωμης</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($payments as $payment)
                <tr>
                  <td>{{ date('m-Y', strtotime($payment->month))}}</td>
                  <td>{{$payment->amount}}€</td>
                  <td>{{ date('d-m-Y', strtotime($payment->payment_date))}}</td>
                  <td>
                    <form action="{{ route('deletepayment', [$payment->id, $client->id]) }}" method="Post">
                      @csrf
                      @method('DELETE')
                      <button class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button>

                    </form>
                  </td>
                  <td>

                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <form id="formAccountSettings" action="{{ route('addpayment') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="clientid" value="{{ $client->id }}">
          <div class="card mb-4">
            <h5 class="card-header">Προσθήκη Πληρωμής</h5>
            <div class="card-body">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Ποσο Πληρωμhς/€</label>
                <input type="number" step="0.01" class="form-control" id="exampleFormControlInput1" name="amount" value="50" placeholder="50" />
              </div>
              <div class="mb-3">
                <label for="html5-month-input" class="form-label">Μhνας Πληρωμής</label>
                <input class="form-control" type="month" value="<?php echo date("Y-m") ?>" id="html5-month-input" name="month" />
              </div>
              <div class="mb-3">
                <label for="html5-datetime-local-input" class="form-label">Ημερομηνια πληρωμής</label>
                <input class="form-control" type="datetime-local" value="" id="html5-datetime-local-input" name="payment_date" />
              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-primary me-2">Προσθήκη</button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection