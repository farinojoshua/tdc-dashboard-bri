<?php

namespace App\Imports\Brisol;

use App\Models\Brisol\Incident;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IncidentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function model(array $row)
    {
        return new Incident([
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
            'resolution_category'  => $row['resolution_category'],
            'priority'             => $row['priority'],
            'status'               => $row['status'],
            'slm_status'           => $row['slm_status'],
        ]);
    }

    private function convertExcelDate($excelDate){
        $unixDate = ($excelDate - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    }
}
