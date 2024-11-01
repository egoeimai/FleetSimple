@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Αθλούμενος /</span> {{ $client->firstName }} {{ $client->lastName }}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Καρτέλα</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('/pages/entrances',$client->id)}}"><i class="bx bx-bell me-1"></i> Παρουσίες</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections',$client->id)}}"><i class="bx bx-link-alt me-1"></i> Πληρωμές</a></li>
    </ul>
    <div class="card mb-4">
      <h5 class="card-header">Στοιχεία Αθλούμενου</h5>
      <!-- Account -->
      <form id="formAccountSettings" action="{{ route('clients.update') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="clientid" value="{{ $client->id }}">

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
              <label for="firstName" class="form-label">Ονομα</label>
              <input class="form-control" type="text" id="firstName" name="firstName" value="{{ $client->firstName }}" autofocus />
            </div>
            <div class="mb-3 col-md-6">
              <label for="lastName" class="form-label">Επιθετο</label>
              <input class="form-control" type="text" name="lastName" id="lastName" value="{{ $client->lastName }}" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input class="form-control" type="text" id="email" name="email" value="{{ $client->email }}" placeholder="john.doe@example.com" />
              @error('email')
              <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label for="html5-datetime-local-input-birthday" class=" form-label">Ημερομηνια Γεννησης</label>

              <input class="form-control" type="datetime-local" value="{{ $client->birth_day }}" id="html5-datetime-local-input-birthday" name="birth_day" />
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="phoneNumber">Τηλεφωνο</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"></span>
                <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="{{ $client->phoneNumber }}" placeholder="202 555 0111" />
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="address" class="form-label">Διευθυνση</label>
              <input type="text" class="form-control" id="address" name="address" value="{{ $client->address }}" placeholder="Address" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="state" class="form-label">Πολη</label>
              <input class="form-control" type="text" id="state" name="state" value="{{ $client->state }}" placeholder="California" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="zipCode" class="form-label">Τ.Κ</label>
              <input type="text" class="form-control" id="zipCode" name="zipCode" value="{{ $client->zipCode }}" placeholder="231465" maxlength="6" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="html5-datetime-local-input" class="col-form-label">Ημερομηνια Εγγραφης</label>
              <div class="col-md-12">
                <input class="form-control" type="datetime-local" value="{{ $client->start_date }}" id="html5-datetime-local-input" name="start_date" />
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="category_athlete" class="form-label">Κατηγορια Αθλητή</label>
              <select id="category_athlete" class="form-select" name="category_athlete">
                <option>Default select</option>
                <option value="box" {{$client->category_athlete == "box"  ? "selected" : ""}}>Box</option>
                <option value="fintess" {{$client->category_athlete == "fintess"  ? "selected" : ""}}>Fitness</option>
                <option value="kids" {{$client->category_athlete == "kids"  ? "selected" : ""}}>Kids</option>







              </select>

            </div>
            <div class="mb-3 col-md-6">
              <div class="card mb-4">
                <h5 class="card-header">Αθλητικά Έγγραφα</h5>
                <div class="card-body demo-vertical-spacing demo-only-element">


                  <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="1" id="doctor" name="doctor" @if($client->doctor == 1 ) checked @endif >
                    <label class="form-check-label" for="doctor">
                      Κάρτα Υγείας
                    </label>
                  </div>
                  <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="1" id="dltio" name='dltio' @if($client->dltio == 1 ) checked @endif >
                    <label class="form-check-label" for="dltio">
                      Δελτίο Ομοσπονδίας
                    </label>
                  </div>




                </div>
              </div>

            </div>

          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Ενημέρωση</button>
            <a href="{{ route('clients') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>

        </div>
      </form>

      <!-- /Account -->
    </div>
    <div class="card">
      <h5 class="card-header">Διαγραφή Χρήστη</h5>
      <div class="card-body">
        <div class="mb-3 col-12 mb-0">
          <div class="alert alert-warning">
            <h6 class="alert-heading fw-bold mb-1">Είσαι σίγουρος πως θες να διαγράψεις αυτόν τον χρήστη;</h6>
            <p class="mb-0">Εάν προχωρήσεις με αυτη την ενέργεια δεν μπορεί να ανερεθεί</p>
          </div>
        </div>
        <form id="formAccountDeactivation" onsubmit="return false">
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
            <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
          </div>
          <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection