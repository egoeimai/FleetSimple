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

<script>
    function formatDate(value) {
        let date = new Date(value);
        const day = date.toLocaleString('el-GR');

        return day;
    }



    function get_expenses(_token, dates) {

        const date = new Date();

        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();

        const withSlashes = [year, month].join('-');

        $.ajax({
            type: 'POST',
            url: '{!! route("earnings.expenses") !!}',
            data: {
                _token: _token,
                month: withSlashes,
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

                    $('#tοtal-expenses').text(earnings);
                })



            },
            error: function(xhr, status, error) {
                // Handle errors if needed
                console.error(xhr.responseText);
            }
        });

    }

    function get_entrances(_token, date) {

        $.ajax({
            type: 'POST',
            url: '{!! route("entrances.date_entrances") !!}',
            data: {
                _token: _token,
                date: date,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                // Handle the response message



                coming = response['modal'];

                console.log(coming)

                const groups = coming.reduce((groups, game) => {
                    const date = game.coming_date.split(' ')[1];
                    const hour = date.split(':')[0];
                    console.log(hour)

                    if (!groups[hour]) {
                        groups[hour] = [];
                    }
                    groups[hour].push(game);
                    return groups;
                }, {});

                // Edit: to add it in the array format instead
                const groupArrays = Object.keys(groups).map((hour) => {
                    return {
                        hour,
                        persons: groups[hour].length
                    };
                });

                $('#athlets_per_hour').empty();
                console.log(groupArrays);
                $.each(groupArrays, function(index, value) {
                    $('#athlets_per_hour').append('<li class="list-group-item d-flex justify-content-between align-items-center">' + value['hour'] + ':00 <span class="badge bg-primary">' + value['persons'] + '</span></li>')


                })


                $('#endrances-today').empty();
                let earnings = 0;
                $.each(coming, function(index, value) {

                    $('#endrances-today').append('<tr><td>' + formatDate(value["coming_date"]) + '</td><td>' + value["firstName"] + ' ' + value["lastName"] + '</td><td>' + value["payment"] + '</td></tr>'


                    )

                })



            },
            error: function(xhr, status, error) {
                // Handle errors if needed
                console.error(xhr.responseText);
            }
        });

    }

    function get_earnings(_token, months) {

        const date = new Date();

        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();

        const withSlashes = [year, month].join('-');

        $.ajax({
            type: 'POST',
            url: '{!! route("earnings.get") !!}',
            data: {
                _token: _token,
                month: withSlashes,
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
            var date = $("input[name='date']").val();
            console.log(date)
            get_entrances(_token, date);


        });

        // Serialize the form data
        var _token = $("input[name='_token']").val();
        var date = $("input[name='date']").val();
        console.log(date)
        get_entrances(_token, date);
        get_expenses(_token, date)
        get_earnings(_token, date);
        // Send an AJAX request


    });
</script>
@endsection




@section('content')
<div class="row">

    <!-- Total Revenue -->
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="col-lg-10 col-md-10 col-12 mb-md-0 mb-4">
                    <div class="card-header">
                        <form id="formAccountSettings" action="#" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="mb-3">
                                <label for="html5-datetime-local-input" class="form-label">Επιλεξτε Ημέρα</label>

                                <div class="input-group">
                                    <input class="form-control" type="date" value="<?php echo date("Y-m-d") ?>" id="html5-month-input" name="date" />
                                    <button class="btn btn-primary" type="button" id="button-addon2">ΕΠΙΛΟΓΗ</button>
                                </div>
                            </div>


                        </form>
                    </div>
                    <div class="card">

                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ημερα/Ωρα</th>
                                        <th>Αθλουμενος</th>
                                        <th>πληρωμη</th>

                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" id="endrances-today">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="card-header">
                        <small class="fw-semibold">Αθλητές Ανα Ώρα</small>
                    </div>

                    <div class="mt-0">
                        <ul class="list-group list-group-flush" id="athlets_per_hour">

                        </ul>
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

                        </div>
                        <span class="d-block mb-1">Έξοδα</span>
                        <h3 class="card-title text-nowrap mb-2"><span id="tοtal-expenses">0</span> €</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded">
                            </div>

                        </div>
                        <span class="fw-semibold d-block mb-1">Έσοδα</span>
                        <h3 class="card-title mb-2"><span id="tοtal-earnigs">0</span> €</h3>
                    </div>
                </div>
            </div>
            <!-- </div>
    <div class="row"> -->

        </div>
    </div>
</div>

@endsection