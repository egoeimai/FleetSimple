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


    function destroy(value_id) {
        var _token = $("input[name='_token']").val();
        $.ajax({
            type: 'DELETE',
            url: '{!! route("destroy_expenses") !!}',
            data: {
                _token: _token,
                value_id: value_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                // Handle the response message



                coming = response['modal'];
                var _token = $("input[name='_token']").val();
                var month = $("input[name='month']").val();
                console.log(month)
                get_earnings(_token, month);


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
                $('#earnings-table').empty();
                let earnings = 0;
                $.each(coming, function(index, value) {
                    earnings = earnings + Number(value["amount"])
                    $('#earnings-table').append('<tr><td>' + formatDate(value["payment_date"]) + '</td><td>' + value["amount"] + '€</td><td>' + value["category"] + '</td><td>' + value["description"] + '</td><td><a class="dropdown-item" href="javascript:void(0);" onclick="destroy(' + value["id"] + ')"><i class="bx bx-trash me-1"></i> Delete</a></td></tr>'


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
    <span class="text-muted fw-light">Οικονομικά /</span> Έξοδα
</h4>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link" href="{{url('/pages/financial')}}"><i class="bx bxs-bank me-1"></i> Έσοδα - Έξοδα</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/earnings')}}"><i class="bx bx-money me-1"></i>Έσοδα</a></li>
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bxs-cart-download me-1"></i>Έξοδα</a></li>
        </ul>
        <div class="row">
            <div class="col-md-8 col-xl-6 col-12 mb-md-0 mb-4">
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
                        Έξοδα: <span id="tοtal-earnigs">0</span> €
                    </h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Μηνας</th>
                                    <th>Ποσο</th>

                                    <th>Κατηγορία</th>
                                    <th>Περιγραφη</th>
                                    <th>Ενεργειες</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="earnings-table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4  col-xl-4 col-12">
                <form id="formAccountSettings" action="{{ route('addexpense') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-4">
                        <h5 class="card-header">Προσθήκη Εξόδου</h5>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Ποσο Πληρωμhς/€</label>
                                <input type="number" step="0.01" class="form-control" id="exampleFormControlInput1" name="amount" value="50" placeholder="50" />
                            </div>
                            <div class="mb-3">
                                <label for="defaultSelect-month-input" class="form-label">Κατηγορια</label>
                                <select id="defaultSelect" class="form-select" name="category">
                                    <option>Default select</option>
                                    <option value="Ρεύμα">Ρεύμα</option>
                                    <option value="Νερό">Νερό</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Εξοπλισμός">Εξοπλισμός</option>
                                    <option value="Ενοίκιο">Ενοίκιο</option>
                                    <option value="Ασφάλιση">Ασφάλιση</option>
                                    <option value="Άλλο Έξοδο">Άλλο Έξοδο</option>






                                </select>

                            </div>
                            <div class="mb-3">
                                <label for="html5-datetime-local-input" class="form-label">Ημερομηνια πληρωμhς</label>
                                <input class="form-control" type="datetime-local" value="" id="html5-datetime-local-input" name="payment_date" />
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Περιγραφh</label>
                                <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
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