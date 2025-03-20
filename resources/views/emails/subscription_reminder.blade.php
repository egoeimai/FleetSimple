<?php
/**
 * Subscription Reminder Email Template
 *
 * @var string $greeting
 * @var string $message
 * @var array $servicesByVehicle
 * @var float $totalCost
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subscription Reminder</title>
</head>
<body>
    <!-- Dynamic Greeting -->
    <h2>{{ $greeting }}</h2>

    <!-- Custom Admin-Defined Greeting (If Provided) -->
    @if (!empty($customGreeting))
        <h3>{{ $customGreeting }}</h3>
    @endif

    <p>Below is a list of services that will expire soon.</p>

    @if (!empty($servicesByVehicle))
        <h3>🔔 Expiring Services:</h3>
        @foreach($servicesByVehicle as $vehicleData)
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
    @endif

    <!-- Total Renewal Cost -->
    <h3>💶 Total Cost for Renewals: €{{ number_format($totalCost, 2) }}</h3>

    <p>Please make the necessary arrangements to renew your services.</p>

    <p>Thank you,<br> Your Company</p>
</body>
</html>
