@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container">
    <h1>📧 Sent Email Details</h1>

    <p><strong>Client:</strong> {{ $sentEmail->client->email }}</p>
    <p><strong>Email:</strong> {{ $sentEmail->email }}</p>
    <p><strong>Sent At:</strong> {{ $sentEmail->created_at->format('d M Y, H:i') }}</p>
    <p><strong>Greeting:</strong> {{ $sentEmail->greeting }}</p>
    <p><strong>Custom Message:</strong> {{ $sentEmail->custom_message }}</p>
    <h3>Expiring Services:</h3>

    @foreach($sentEmail->subscriptions as $vehicleData)
        <h4>🚗 Vehicle: {{ $vehicleData['vehicle'] }}</h4>
        <ul>
            @foreach($vehicleData['services'] as $service)
                <li>
                    <strong>🛠 Service:</strong> {{ $service['service'] }} <br>
                    <strong>📅 Renewal Date:</strong> {{ $service['renewal_date'] }} <br>
                    <strong>💰 Cost:</strong> €{{ number_format($service['cost'], 2) }}
                </li>
            @endforeach
        </ul>
    @endforeach

    <h3>Total Cost for Renewals: €{{ number_format($sentEmail->total_cost, 2) }}</h3>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
