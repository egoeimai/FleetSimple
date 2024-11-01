@extends('layouts/contentNavbarLayout')

@section('title', 'Finacial Dashboard')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
<script>
    function formatDate(value) {
        let date = new Date(value);
        const day = date.toLocaleString('default', {
            day: '2-digit'
        });
        const month = date.toLocaleString('default', {
            month: 'numeric'
        });
        const year = date.toLocaleString('default', {
            year: 'numeric'
        });
        return day + '/' + month + '/' + year;
    }

    function get_expenses(_token, month) {
        let earnings = 0;
        $('#tοtal-expenses').text(earnings);
        $.ajax({
            type: 'POST',
            url: '{!! route("earnings.expenses") !!}',
            data: {
                _token: _token,
                month: month,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                // Handle the response message



                coming = response['modal'];

                console.log(coming)
                $('#expenses-table').empty();
                let earnings = 0;
                $.each(coming, function(index, value) {
                    earnings = earnings + Number(value["amount"])
                    $('#expenses-table').append('<tr><td>' + formatDate(value["payment_date"]) + '</td><td>' + value["amount"] + '€</td><td>' + value["category"] + '</td><td>' + value["description"] + '</td></tr>'


                    )
                    $('#tοtal-expenses').text(earnings);
                })



            },
            error: function(xhr, status, error) {
                // Handle errors if needed
                console.error(xhr.responseText);
            }
        });

    }

    function get_earnings(_token, month) {
        let earnings = 0;
        $('#tοtal-earnigs').text(earnings);
        $.ajax({
            type: 'POST',
            url: '{!! route("earnings.get") !!}',
            data: {
                _token: _token,
                month: month,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                // Handle the response message



                coming = response['modal'];

                console.log(coming)
                $('#earnings-table').empty();
                let earnings = 0;
                $.each(coming, function(index, value) {
                    earnings = earnings + Number(value["amount"])
                    $('#earnings-table').append('<tr><td>' + formatDate(value["payment_date"]) + '</td><td>' + value["amount"] + '€</td><td>Συνδρομή</td><td>' + value["firstName"] + ' ' + value["lastName"] + '</td></tr>'


                    )
                    $('#tοtal-earnigs').text(earnings);
                })



            },
            error: function(xhr, status, error) {
                // Handle errors if needed
                console.error(xhr.responseText);
            }
        });

    }

    $(document).ready(function() {


        $("#button-addon2").click(function(e) {
            e.preventDefault();
            var _token = $("input[name='_token']").val();
            var month = $("input[name='month']").val();
            console.log(month)
            get_expenses(_token, month);
            get_earnings(_token, month);

        });

        // Serialize the form data
        var _token = $("input[name='_token']").val();
        var month = $("input[name='month']").val();
        console.log(month)
        get_expenses(_token, month);
        get_earnings(_token, month);
        // Send an AJAX request


    });
</script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Οικονομικά /</span> Έσοδα - Έξοδα
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bxs-bank me-1"></i> Έσοδα - Έξοδα</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('/pages/earnings')}}"><i class="bx bx-money me-1"></i>Έσοδα</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/expenses')}}"><i class="bx bxs-cart-download me-1"></i>Έξοδα</a></li>
        </ul>
        <div class="row">
            <div class=" col-4 mb-md-0 mb-4">
                <form id="formAccountSettings" action="#" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="html5-datetime-local-input" class="form-label">Επιλεξτε μηνα</label>

                        <div class="input-group">
                            <input class="form-control" type="month" value="<?php echo date("Y-m") ?>" id="html5-month-input" name="month" />
                            <button class="btn btn-primary" type="button" id="button-addon2">ΕΠΙΛΟΓΗ</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-12 mb-md-0 mb-4">
                <!-- Basic Bootstrap Table -->

                <div class="card">


                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Μηνας</th>
                                    <th>Ποσο</th>
                                    <th>Περιγραφη</th>
                                    <th>Αθλουμενος</th>

                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="earnings-table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12 mb-md-0 mb-4">
                <!-- Basic Bootstrap Table -->

                <div class="card">


                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Μηνας</th>
                                    <th>Ποσο</th>
                                    <th>Περιγραφη</th>
                                    <th>Κατηγορια</th>

                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="expenses-table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded">
                            </div>

                        </div>
                        <span class="fw-semibold d-block mb-1">Τζίρος</span>
                        <h3 class="card-title mb-2"><span id="tοtal-earnigs">0</span> €</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="Credit Card" class="rounded">
                            </div>

                        </div>
                        <span class="d-block mb-1">Έξοδα</span>
                        <h3 class="card-title text-nowrap mb-2"><span id="tοtal-expenses">0</span> €</h3>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>
@endsection