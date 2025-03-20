@extends('layouts/contentNavbarLayout')

@section('title', 'Προσθήκη Υπηρεσίας')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Ρυθμίσεις</span>
</h4>





<div class="container">
    <h1>Email Reminder Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Exclude Specific Dates -->
        <div class="mb-3">
            <label class="form-label">Exclude Specific Dates (e.g., Public Holidays)</label>
            <input type="text" name="excluded_dates" class="form-control" 
                   value="{{ implode(',', $settings->excluded_dates ?? []) }}">
            <small class="text-muted">Enter dates in YYYY-MM-DD format, separated by commas (e.g., 2025-08-15,2025-12-25)</small>
        </div>

        <!-- Exclude Weekends -->
        <div class="mb-3">
            <label class="form-label">Exclude Weekends</label>
            <select name="excluded_days[]" class="form-select" multiple>
                <option value="Saturday" {{ in_array('Saturday', $settings->excluded_days ?? []) ? 'selected' : '' }}>Saturday</option>
                <option value="Sunday" {{ in_array('Sunday', $settings->excluded_days ?? []) ? 'selected' : '' }}>Sunday</option>
            </select>
        </div>

        <!-- Email Days -->
        <div class="mb-3">
            <label class="form-label">Days Before Expiration to Send Email</label>
            <input type="text" name="email_days[]" class="form-control" value="{{ implode(',', $settings->email_days ?? [30, 15, 5]) }}">
            <small class="text-muted">Enter days as a comma-separated list (e.g., 30,15,5)</small>
        </div>

        <!-- Custom Greeting -->
        <div class="mb-3">
            <label class="form-label">Email Greeting Text</label>
            <input type="text" name="greeting_text" class="form-control" value="{{ $settings->greeting_text ?? '' }}">
        </div>

        <!-- Custom Email Message -->
        <div class="mb-3">
            <label class="form-label">Email Message</label>
            <textarea name="email_message" class="form-control" rows="4">{{ $settings->email_message ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
