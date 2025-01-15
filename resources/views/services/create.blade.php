@extends('layouts/contentNavbarLayout')

@section('title', 'Προσθήκη Υπηρεσίας')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Υπηρεσίες /</span> Προσθήκη Υπηρεσίας
</h4>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Στοιχεία Υπηρεσίας</h5>
            <!-- Account -->
            <form id="formAccountSettings" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">


                <div class="card-body">

                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="title " class="form-label">Τίτλος Υπηρεσίας</label>
                            <input class="form-control" type="text" id="title" name="title" placeholder="Τίτλος Υπηρεσίας" value="{{ old('title') }}" required />
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="brand" class="form-label">Ενδεικτικό Κόστος</label>
                            <input class="form-control" type="number" name="default_cost" id="default_cost" class="form-control" placeholder="Enter default cost" value="{{ old('default_cost') }}" step="0.01" min="0" required>
                            @error('default_cost')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>







                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Προσθήκη</button>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </div>
            </form>
            <!-- /Account -->
        </div>
    </div>
</div>
@endsection