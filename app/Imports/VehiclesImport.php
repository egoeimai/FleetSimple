<?php

namespace App\Imports;

use App\Models\Clients;
use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiclesImport implements ToModel
{
    public function model(array $row)
    {
        $client = Clients::where('old_id', $row[0])->first();

        if (!$client || empty($row[4])) {
            return null;
        }

        return Vehicle::firstOrCreate([
            'license_plate' => $row[4],
        ], [
            'client_id' => $client->id,
        ]);
    }
}

