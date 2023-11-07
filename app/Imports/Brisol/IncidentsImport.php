<?php

namespace App\Imports\Brisol;

use App\Models\Brisol\Incident;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;

class IncidentsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            $incident = Incident::where('inc_id', $rowArray['incident_number'])->first();

            if ($incident) {
                $updatedFields = $this->checkForUpdates($incident, $rowArray);
                if (!empty($updatedFields)) {
                    $incident->update($updatedFields);
                }
            } else {
                Incident::create($this->modelArray($rowArray));
            }
        }
    }

    private function modelArray(array $row)
    {
        return [
            'inc_id'               => $row['incident_number'],
            'reported_date'        => $this->convertExcelDate($row['reported_date']),
            'resolved_date'        => $this->convertExcelDate($row['last_resolved_date']),
            'region'               => $row['region'],
            'service_ci'           => $row['serviceci'],
            'prd_tier1'            => $row['product_categorization_tier_1'],
            'prd_tier2'            => $row['product_categorization_tier_2'],
            'prd_tier3'            => $row['product_categorization_tier_3'],
            'ctg_tier1'            => $row['categorization_tier_1'],
            'ctg_tier2'            => $row['categorization_tier_2'],
            'ctg_tier3'            => $row['categorization_tier_3'],
            'reported_source'      => $row['reported_source'],
            'resolution_category'  => $row['resolution_category'],
            'priority'             => $row['priority'],
            'status'               => $row['status'],
            'slm_status'           => $row['slm_status'],
        ];
    }

    private function checkForUpdates(Incident $incident, array $row)
    {
        $attributes = $this->modelArray($row);
        $changes = [];

        foreach ($attributes as $key => $value) {
            if ($incident->$key != $value) {
                $changes[$key] = $value;
            }
        }

        return $changes;
    }

    private function convertExcelDate($excelDate)
    {
        $unixDate = ($excelDate - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    }
}
