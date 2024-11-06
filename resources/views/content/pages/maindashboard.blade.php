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

</div>
</div>

@endsection