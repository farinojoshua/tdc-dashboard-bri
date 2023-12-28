<?php

namespace App\Imports\UserManagement;

use App\Models\UserManagement\Incident;
use App\Models\UserManagement\Branch;
use App\Models\UserManagement\ReqType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class IncidentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $branchCode = ltrim($row['branch_code'], '0'); // Menghapus nol di awal

            // Pastikan panjang branch_code tidak melebihi 4 digit
            if (strlen($branchCode) > 4) {
                // Anda bisa menentukan tindakan yang sesuai di sini
                return null; // Misalnya, abaikan atau tangani kasus yang spesifik
            }

            $branchCode = str_pad($branchCode, 4, '0', STR_PAD_LEFT);

            if (!Branch::where('branch_code', $branchCode)->exists()) {
                // Jika tidak ada, mengabaikan baris ini
                return null;
            }

            $execStatus = $row['tanggal_dikerjakan'] ? 'Done' : 'Pending';

            return new Incident([
                'reported_date' => $this->parseIndonesianDate($row['tanggal_disetujui']),
                'req_type' => $row['jenis_pengajuan'],
                'branch_code' => $branchCode,
                'req_status' => $row['status_pengajuan'],
                'exec_status' => $execStatus,
                'execution_date' => $row['tanggal_dikerjakan'] ? $this->parseIndonesianDate($row['tanggal_dikerjakan']) : null,
                'sla_category' => $execStatus === 'Done' ? ($this->parseIndonesianDate($row['tanggal_dikerjakan']) === $this->parseIndonesianDate($row['tanggal_disetujui']) ? 'Meet SLA' : 'Over SLA') : null,
            ]);
        });
    }

    private function parseIndonesianDate($dateInput) {
        if (is_numeric($dateInput)) {
            return Date::excelToDateTimeObject($dateInput)->format('Y-m-d');
        }

        $months = [
            'Jan' => 'Jan',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Apr',
            'Mei' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Agu' => 'Aug',
            'Sep' => 'Sep',
            'Okt' => 'Oct',
            'Nov' => 'Nov',
            'Des' => 'Dec',
        ];

        foreach ($months as $id => $en) {
            $dateInput = str_replace($id, $en, $dateInput);
        }

        return Carbon::createFromFormat('d-M-y', $dateInput)->format('Y-m-d');
    }
}
