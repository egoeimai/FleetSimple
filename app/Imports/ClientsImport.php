<?php

namespace App\Imports;

use App\Models\Clients;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ClientsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $uniqueClients = [];

        foreach ($rows as $row) {
            // Skip empty rows or rows missing essential columns
            if (!isset($row[0]) || !isset($row[1])) {
                continue;
            }

            $oldId = trim($row[0]);
            $email = trim($row[1]);

            // Skip if old_id already processed or email is empty
            if (empty($oldId) || empty($email) || isset($uniqueClients[$oldId])) {
                continue;
            }

            $uniqueClients[$oldId] = true;

            Clients::updateOrCreate(
                ['old_id' => $oldId],
                [
                    'email'   => $email,
                    'email_2' => $row[2] ?? null,
                    'email_3' => $row[3] ?? null,
                ]
            );
        }
    }
    
}