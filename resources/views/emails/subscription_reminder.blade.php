<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>FleetSimple - Υπενθύμιση Συνδρομής</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <!-- Χαιρετισμός -->
    <h2>{{ $greeting }}</h2>

    @if (!empty($customGreeting))
        <p><strong>{{ $customGreeting }}</strong></p>
    @endif

    <p>
        Σας ενημερώνουμε ότι η ετήσια συνδρομή των παρακάτω υπηρεσιών πρόκειται να λήξει στις αντίστοιχες ημερομηνίες.
    </p>

    @if (!empty($groupedSubscriptions))
        <h3>Επερχόμενες Λήξεις Υπηρεσιών:</h3>
        @foreach($groupedSubscriptions as $date => $vehicles)
            <h4>Ημερομηνία Λήξης: {{ $date }}</h4>
            @foreach($vehicles as $vehicle => $services)
                <h5>Όχημα: {{ $vehicle }}</h5>
                <ul>
                    @foreach($services as $service)
                        <li>
                            Υπηρεσία: {{ $service['service'] }}<br>
                            Ημερομηνία Λήξης: {{ $service['renewal_date'] }}<br>
                            Κόστος: €{{ number_format($service['cost'], 2) }}
                        </li>
                    @endforeach
                </ul>
            @endforeach
        @endforeach
    @endif

    <h3>Συνολικό Ποσό Ανανέωσης: €{{ number_format($totalCost, 2) }}</h3>

    <h3>Τραπεζικοί Λογαριασμοί για Πληρωμή:</h3>

    <p><strong>Σημείωση:</strong> Αν η πληρωμή γίνει από διαφορετική τράπεζα ή μέσω τρίτης υπηρεσίας (Revolut, VivaWallet κ.α.), παρακαλούμε προσθέστε 4€ επιπλέον.</p>

    <ul>
        <li><strong>Eurobank</strong><br>
        IBAN: GR5802600990000320200675895<br>
        Δικαιούχος: FleetSimple Μονοπρόσωπη ΙΚΕ</li>

        <li><strong>Πειραιώς</strong><br>
        IBAN: GR1801715580006558146790758<br>
        Δικαιούχος: FleetSimple Μονοπρόσωπη ΙΚΕ</li>

        <li><strong>Εθνική Τράπεζα</strong><br>
        IBAN: GR2601101820000018200421370<br>
        Δικαιούχος: FleetSimple Μονοπρόσωπη ΙΚΕ</li>

        <li><strong>Alpha Bank</strong><br>
        IBAN: GR2101403490349002002009969<br>
        Δικαιούχος: FleetSimple Μονοπρόσωπη ΙΚΕ</li>
    </ul>

    <p>
        Παρακαλούμε σημειώστε το ονοματεπώνυμό σας ή τον αριθμό κυκλοφορίας του οχήματος ως αιτιολογία πληρωμής.
    </p>

    <p>Σας ευχαριστούμε για τη συνεργασία.<br>Η ομάδα του FleetSimple</p>
</body>
</html>
