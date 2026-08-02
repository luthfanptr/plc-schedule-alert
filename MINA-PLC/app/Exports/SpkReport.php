<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpkReport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(protected Collection $data){}

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'SPK Number',
            'PLC ID',
            'Line',
            'Line Name',
            'Component',
            'Status',
            'SPK Status',
            'Teknisi',
            'Start Date',
            'Finish Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->spk_number,
            $row->plc_id,
            $row->line,
            trim($row->line_name),
            trim($row->component_name),
            $row->status,
            $row->spk_status,
            $row->teknisi,
            $row->spk_start_date ? Carbon::parse($row->spk_start_date)->format('d M Y H:i') : '-',
            $row->spk_finish_date ? Carbon::parse($row->spk_finish_date)->format('d M Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
