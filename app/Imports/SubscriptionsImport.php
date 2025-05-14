<?php

namespace App\Imports;

use App\Models\Clients;
use App\Models\Vehicle;
use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class SubscriptionsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row[0]) || empty($row[4])) {
                continue;
            }

            $client = Clients::where('old_id', $row[0])->first();
            if (!$client) continue;

            $vehicle = Vehicle::where('license_plate', $row[4])
                ->where('client_id', $client->id)
                ->first();


            $renewalDate = $this->formatDate($row[5]);
            if (!$renewalDate) continue; // skip if invalid date

            $months = $row[6];

            if (!$vehicle) continue;

            $renewalDate = $this->formatDate($row[5]);
            $months = $row[6];

            // Define service mapping: column => service_id
            $services = [
                7  => 1, // βασικό πακέτο
                8  => 5, // ζώνη ελέγχου (assumed)
                9  => 2, // sms (assumed)
                10 => 4, // calls
                11 => 6, // panic (assumed)
                12 => 7, // kls
            ];

            foreach ($services as $column => $serviceId) {
                $amount = $row[$column] ?? null;
                if (empty($amount)) continue;

                Subscription::create([
                    'vehicle_id'    => $vehicle->id,
                    'service_id'    => $serviceId,
                    'start_date'    => now(),
                    'renewal_date'  => $renewalDate,
                    'total_cost'    => $amount,
                    'amount_due'    => $amount,
                    'status'        => 'active',
                ]);
            }
        }
    }

    private function formatDate($value)
    {
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        }

        try {
            return \Carbon\Carbon::createFromFormat('d/m', $value)->setYear(now()->year);
        } catch (\Exception $e) {
            return null;
        }
    }
}
