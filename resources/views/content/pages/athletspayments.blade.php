@extends('layouts/contentNavbarLayout')

@section('title', 'Πληρωμές Αθλητως')



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

    function get_payment_earnings(_token, month) {
        let count_comes = 0;
        $('#count_comes').text(count_comes);


        $.ajax({
            type: 'POST',
            url: '{!! route("athlets-payments.get") !!}',
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
                console.log(coming);
                count_comes = response['count'];
                $('#payments-table').empty();
                $('#count_comes').text(count_comes);

                $.each(coming, function(index, value) {

                    doctor_paper = '<span class="badge bg-label-warning me-1">Δεν υπάρχει</span>'

                    if (value["doctor"] == 1) {

                        doctor_paper = '<span class="badge bg-label-primary me-1">εχει προσκομησθει</span>'
                    }

                    deltio_paper = '<span class="badge bg-label-warning me-1">Δεν υπάρχει</span>'

                    if (value["dltio"] == 1) {

                        deltio_paper = '<span class="badge bg-label-primary me-1">εχει προσκομησθει</span>'
                    }

                    payment = '<span class="badge bg-label-warning me-1">Δεν έχει πληρώσει</span>'

                    if (value["payment_date"] && value["amount"]) {

                        payment = value["payment_date"] + ' / ' + value["amount"] + '€'
                    }


                    $('#payments-table').append('<tr><td><a href="https://evolution.devwizards.gr/pages/clients-edit/' + value["clientid"] + '">' + value["firstName"] + ' ' + value["lastName"] + '</a></td><td>' + value["phoneNumber"] + '</td><td>' + doctor_paper + '</td><td>' + deltio_paper + '</td><td>' + payment + '</td><td>' + value['comes'] + '</td></tr>'


                    )


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
            get_payment_earnings(_token, month);

        });


        var _token = $("input[name='_token']").val();
        var month = $("input[name='month']").val();
        console.log(month)
        get_payment_earnings(_token, month);



    });
</script>
@endsection


@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Αθλητές /</span> Σύνολο <span id="count_comes">0</span> Αθλητές
</h4>

<!-- Basic Bootstrap Table -->

<div class="row">

    <div class="card mb-4 col-md-4">

        <div class="card-body">
            <form id="formAccountSettings" action="#" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="mb-3">
                    <label for="html5-datetime-local-input" class="form-label">Επιλεξτε Μηνα</label>

                    <div class="input-group">
                        <input class="form-control" type="month" value="<?php echo date("Y-m") ?>" id="html5-month-input" name="month" />
                        <button class="btn btn-primary" type="button" id="button-addon2">ΕΠΙΛΟΓΗ</button>
                    </div>
                </div>


            </form>

        </div>
    </div>
    <hr class="m-0" />




</div>

<div class="row">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="card">

        <h5 class="card-header">Αθλητές</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Όνομα</th>
                        <th>ΤΗΛΕΦΩΝΟ</th>

                        <th>Καρτα Υγειας</th>
                        <th>δελτιο Ομοσπονδίας</th>
                        <th>Πληρωμή</th>
                        <th>Παρουσίες</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="payments-table">
                    @foreach ($clients as $client)
                    <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $client->firstName }} {{ $client->lastName }}</strong></td>
                        <td>{{ $client->phoneNumber }}</td>
                        <td>
                            @if ($client->doctor == 1)
                            <span class="badge bg-label-primary me-1">εχει προσκομησθει</span>

                            @else
                            <span class="badge bg-label-warning me-1">Δεν υπάρχει</span>

                            @endif
                        </td>
                        <td> @if ($client->dltio == 1)
                            <span class="badge bg-label-primary me-1">εχει προσκομησθει</span>

                            @else
                            <span class="badge bg-label-warning me-1">Δεν υπάρχει</span>

                            @endif
                        </td>
                        <td>

                            {{$client->payment_date}} | {{ $client->amount }} €
                        </td>


                        <td>
                            {{$client->comes}}
                        </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    <hr class="my-5">
</div>

<!--/ Responsive Table -->
@endsection