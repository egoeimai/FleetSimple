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

    function formatmonth(value) {
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
        return month + '/' + year;
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


                $('#earnings-table').empty();

                $.each(coming, function(index, value) {
                    earnings = earnings + Number(value["amount"])
                    $('#earnings-table').append('<tr><td>' + formatDate(value["payment_date"]) + '</td><td>' + value["amount"] + '€</td><td>Συνδρομή : ' + formatmonth(value["month"]) + '</td><td>' + value["firstName"] + ' ' + value["lastName"] + '</td><td><button class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button></td></tr>'


                    )
                    console.log(earnings);
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
            get_earnings(_token, month);

        });

        // Serialize the form data
        var _token = $("input[name='_token']").val();
        var month = $("input[name='month']").val();
        console.log(month)
        get_earnings(_token, month);
        // Send an AJAX request


    });
</script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Οικονομικά /</span> Έσοδα
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-lg-row mb-3">
            <li class="nav-item"><a class="nav-link" href="{{url('/pages/financial')}}"><i class="bx bxs-bank me-1"></i> Έσοδα - Έξοδα</a></li>
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-money me-1"></i>Έσοδα</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/expenses')}}"><i class="bx bxs-cart-download me-1"></i>Έξοδα</a></li>
        </ul>

        <div class="row">
            <div class="col-xl-12 col-12 mb-md-0 mb-4">
                <!-- Basic Bootstrap Table -->
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
                <div class="card">

                    <h5 class="card-header">
                        Έσοδα: <span id="tοtal-earnigs">0</span> €
                    </h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Μηνας</th>
                                    <th>Ποσο</th>
                                    <th>Περιγραφη</th>
                                    <th>Αθλουμενος</th>
                                    <th>Ενεργειες</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="earnings-table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>
@endsection