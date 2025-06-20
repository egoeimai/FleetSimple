@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container">
    <h1>📧 Sent Email Details</h1>

    <p><strong>Client:</strong> {{ $sentEmail->client->email }}</p>
    <p><strong>Email:</strong> {{ $sentEmail->client->email }}</p>
    <p><strong>Sent At:</strong> {{ $sentEmail->send_date->format('d M Y') }}</p>
    <p><strong>Greeting:</strong> {{ $sentEmail->greeting }}</p>
    <p><strong>Custom Message:</strong> {{ $sentEmail->custom_message }}</p>
    <h3>Expiring Services:</h3>
	
	@php
    $subscriptions = is_array($sentEmail->subscriptions)
        ? $sentEmail->subscriptions
        : json_decode($sentEmail->subscriptions, true);
@endphp

   @foreach ($subscriptions  as $vehicle => $services)
    <div>
        <strong>Όχημα:</strong> {{ $vehicle }}
        <ul>
            @foreach ($services as $service)
                <div>
                    Υπηρεσία: {{ $service['service'] }}<br>
                    Ημερομηνία Λήξης: {{ $service['renewal_date'] }}<br>
                    Κόστος: €{{ $service['cost'] }}<br>
                    
                </div>
            @endforeach
        </ul>
    </div>
@endforeach


    <h3>Total Cost for Renewals: €{{ number_format($sentEmail->total_cost, 2) }}</h3>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
