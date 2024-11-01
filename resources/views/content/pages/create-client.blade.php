@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Αθλητές /</span> Δημιουργία Νέου Αθλητή
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Βασικά Στοιχεία</a></li>

        </ul>
        <div class="card mb-4">
            <h5 class="card-header">Κάρτα Αθλητή</h5>
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
                            <label for="firstName" class="form-label">Ονομα</label>
                            <input class="form-control" type="text" id="firstName" name="firstName" value="" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Επιθετο</label>
                            <input class="form-control" type="text" name="lastName" id="lastName" value="" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email" value="" placeholder="john.doe@example.com" />
                            @error('email')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="age" class="form-label">Ημερομηνια Γεννησης</label>
                            <input class="form-control" type="datetime-local" value="" id="html5-datetime-local-input" name="birth_day" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Τηλεφωνο</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"></span>
                                <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="202 555 0111" />
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Διευθυνση</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="state" class="form-label">Πολη</label>
                            <input class="form-control" type="text" id="state" name="state" placeholder="California" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="zipCode" class="form-label">Τ.Κ</label>
                            <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="231465" maxlength="6" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="html5-datetime-local-input" class="col-form-label">Ημερομηνια Εγγραφης</label>
                            <div class="col-md-12">
                                <input class="form-control" type="datetime-local" value="2021-06-18T12:30:00" id="html5-datetime-local-input" name="start_date" />
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="category_athlete" class="form-label">Κατηγορια Αθλητή</label>
                            <select id="category_athlete" class="form-select" name="category_athlete">
                                <option>Default select</option>
                                <option value="box">Box</option>
                                <option value="fintess">Fitness</option>
                                <option value="kids">Kids</option>







                            </select>

                        </div>
                        <div class="mb-3 col-md-6">
                            <div class="card mb-4">
                                <h5 class="card-header">Αθλητικά Έγγραφα</h5>
                                <div class="card-body demo-vertical-spacing demo-only-element">


                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="doctor" name="doctor" />
                                        <label class="form-check-label" for="doctor">
                                            Κάρτα Υγείας
                                        </label>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="dltio" name='dltio' />
                                        <label class="form-check-label" for="dltio">
                                            Δελτίο Ομοσπονδίας </label>
                                    </div>




                                </div>
                            </div>

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