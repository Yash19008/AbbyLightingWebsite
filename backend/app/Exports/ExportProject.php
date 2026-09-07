<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportProject implements FromCollection, WithHeadings ,WithStyles,WithMapping, WithTitle, ShouldAutoSize
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
            $row->type	 ?? "",
            $row->name ?? "",
            $row->slug ?? "",
            $row->location ?? "",
            $row->description ?? "",
            $row->sub_tags ?? "",
            $row->sequence ?? "",
            $row->block_column ?? "",
            $row->is_active ?? ""
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
          'Type',
          'Name',
          'Slug',
          'Location',
          'Description',
          'Products',
          'Sequence',
          'Block Column',
          'Is Active ?'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Projects';
    }
}
