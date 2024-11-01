@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')

<script>
    function formatDate(value) {
        let date = new Date(value);
        const day = date.toLocaleString('default', {
            day: '2-digit'
        });
        const month = date.toLocaleString('default', {
            month: 'short'
        });
        const year = date.toLocaleString('default', {
            year: 'numeric'
        });

        const hour = date.toLocaleString('default', {
            hour: '2-digit'
        });

        const minute = date.toLocaleString('default', {
            minute: '2-digit'
        });
        return day + '-' + month + '-' + year + ' ' + hour + ':' + minute;
    }
    $(document).ready(function() {
        $("#button-addon2").click(function(e) {
            e.preventDefault();

            // Serialize the form data
            var _token = $("input[name='_token']").val();
            var month = $("input[name='month']").val();
            var clientid = $("input[name='clientid']").val();
            console.log(month)
            // Send an AJAX request
            $.ajax({
                type: 'POST',
                url: '{!! route("entrances.gomonth") !!}',
                data: {
                    _token: _token,
                    month: month,
                    clientid: clientid
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    // Handle the response message



                    coming = response['modal'];
                    coming.lenght

                    $('#counter-entr').empty();
                    $('#counter-entr').text(Object.keys(coming).length);
                    $('#endrances-month').empty();

                    $.each(coming, function(index, value) {
                        $('#endrances-month').append(

                            '<tr><td>' + formatDate(value["created_at"]) + '</td><td><button class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</button></td>'
                        )



                    })


                },
                error: function(xhr, status, error) {
                    // Handle errors if needed
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>




<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Αθλούμενος /</span> Παρουσίες
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link" href="{{url('pages/clients-edit', $client->id)}}"><i class="bx bx-user me-1"></i> Καρτέλα</a></li>
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-bell me-1"></i> Παρουσίες</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections', $client->id)}}"><i class="bx bx-link-alt me-1"></i> Πληρωμές</a></li>
        </ul>
        <div class="row">
            <div class="col-md-4 col-12 mb-md-0 mb-4">
                <!-- Basic Bootstrap Table -->
                <form id="formAccountSettings" action="#" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="clientid" value="{{ $client->id }}">
                    <div class="mb-3">
                        <label for="html5-datetime-local-input" class="form-label">Επιλεξτε μηνα</label>

                        <div class="input-group">
                            <input class="form-control" type="month" value="<?php echo date("Y-m") ?>" id="html5-month-input" name="month" />
                            <button class="btn btn-primary" type="button" id="button-addon2">ΕΠΙΛΟΓΗ</button>
                        </div>
                    </div>


                </form>
                <div class="card">

                    <h5 class="card-header">Παρουσία - (<span id="counter-entr">{{ $coming->count() }} </span> φορές)</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ημερα/Ωρα</th>
                                    <th>Ενεργρεια</th>

                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="endrances-month">
                                @foreach ($coming as $coming_user)
                                <tr>
                                    <td>{{ date('d-m-Y H:i', strtotime($coming_user->created_at))}}</td>

                                    <td>
                                        <form action="{{ route('deletepayment', [$coming_user->id, $client->id]) }}" method="Post">
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

        </div>
    </div>
</div>
@endsection