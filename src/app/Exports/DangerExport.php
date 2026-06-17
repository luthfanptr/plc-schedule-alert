<?php

namespace App\Exports;

use App\Models\PlcStatus;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DangerExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private int $rowNumber = 0;

    public function __construct(
        public string $plant
    ){}

    // pakai karena query data dari db
    public function query()
    {
        return PlcStatus::query()
            ->where('plant', $this->plant)
            ->where('status', 'DANGER')
            ->where(function ($query) {
                $query->where('spk_status', '!=', 'done')
                        ->orWhereNull('spk_status');   
            });
    }

    public function headings() : array
    {
        $date = now()->format('d/m/Y');

        return [
            ["List Data Komponen Danger {$this->plant}"],
            [$date],
            [],
            // header
            ['No', 'PLC', 'Line', 'Line Name', 'Komponen', 'Status', 'Tanggal Kejadian']
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $row->plc_id,
            $row->line,
            $row->line_name,
            $row->component_name,
            $row->status,
            $row->plc_date,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->rowNumber + 4; // 3 header rows + 1 heading row

        // Border untuk seluruh tabel
        $sheet->getStyle("A4:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true, 'size' => 13]],
            4 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFF00'],
                ],
            ],
        ];
    }
}
