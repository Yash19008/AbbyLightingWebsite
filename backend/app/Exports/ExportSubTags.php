<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportSubTags implements FromCollection, WithHeadings ,WithStyles,WithMapping, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $query = null;
    
    public function  __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query;
    }

    public function map($row): array {
        $fields = [
            $row->display_name	 ?? "",
            $row->name ?? "",
            $row->slug ?? "",
            $row->tags ?? "",
            $row->projects ?? ""
        ];
        return $fields;
    }
    public function styles(Worksheet $sheet)
    {
        return [
        // Style the first row as bold text.
        1    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
          'Display Name',
          'Name',
          'Slug',
          'Tags',
          'Projects'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Sub Tags';
    }
}
