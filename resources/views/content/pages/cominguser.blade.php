@extends('layouts/blankLayout')

@section('title', 'Blank layout - Layouts')



@section('content')


<script>
    $(document).ready(function() {
        $(".btn-submit").click(function(e) {
            e.preventDefault();

            // Serialize the form data
            var _token = $("input[name='_token']").val();
            var email = $("input[name='email']").val();

            // Send an AJAX request
            $.ajax({
                type: 'POST',
                url: '{!! route("coming.search") !!}',
                data: {
                    _token: _token,
                    email: email
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    // Handle the response message

                    if (response.modal == "success-modal" || response.modal == "no-payment") {

                        $('#' + response.modal).modal('show');

                        $('#cf-response-message').text(response.message);
                        setTimeout(function() {
                            $('#' + response.modal).modal('hide');
                            $("input[name='email']").val("");
                        }, 4000);

                    }

                    if (response.modal == "no-modal-doc") {

                        $('#' + response.modal).modal('show');

                        $('#blockquote-doc').text(response.text_doc);
                        setTimeout(function() {
                            $('#' + response.modal).modal('hide');
                            $("input[name='email']").val("");
                        }, 4000);

                    }

                },
                error: function(xhr, status, error) {
                    // Handle errors if needed
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<div class="container">
    <div class="row justify-content-center align-middle">
        <div class="authentication-wrapper authentication-basic container-p-y col-lg-5 col-md-6">
            <div class="authentication-inner py-4">

                <!-- Forgot Password -->
                <div class="card align-middle">
                    <div class="card-body ">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-5">
                            <a href="#" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo/logo.png') }}" class="logo"></span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-10 text-center">Εισάγετε Αριθμό τηλεφώνου</h4>
                        <form id="formAuthentication" class="mb-3" action="javascript:void(0)" method="GET">
                            {{ csrf_field() }}
                            <div class="mb-3">
                                <label for="email" class="form-label"></label>
                                <input type="number" class="form-control" id="email" name="email" placeholder="Τηλέφωνο" autofocus>
                            </div>
                            <button class="btn btn-primary btn-submit d-grid w-100">Ολοκλήρωση</button>
                        </form>
                        <div id="cf-response-message"></div>

                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>


    <div class="modal fade" id="success-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h2 class="modal-title" id="exampleModalLabel1">Καλή προπόνηση</h2>
                    <box-icon name='check-circle' animation='tada' color='#019f17' style="
    width: 150px;
    height: 150px;
"> </box-icon>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="no-modal-doc" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h2 class="modal-title" id="exampleModalLabel1">Καλή προπόνηση</h2>
                    <box-icon name='info-square' animation='burst' rotate='180' color='#fbc705' style="
    width: 150px;
    height: 150px;
"> </box-icon>
                    <figure class="text-center mt-2">
                        <h2 class="blockquote" id="blockquote-doc">
                            Παρακαλώ τακτοποιείστε την οφειλή σας για τον τρέχον μήνα
                        </h2>

                    </figure>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="no-payment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h2 class="modal-title" id="exampleModalLabel1">Καλή προπόνηση</h2>
                    <box-icon name='check-circle' animation='tada' color='#B60505' style="
    width: 150px;
    height: 150px;
"> </box-icon>
                    <figure class="text-center mt-2">
                        <h2 class="blockquote">
                            Παρακαλώ τακτοποιείστε την οφειλή σας για τον τρέχον μήνα
                        </h2>

                    </figure>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection