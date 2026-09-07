<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportProduct implements FromCollection, WithHeadings ,WithStyles,WithMapping, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $query = null;
    protected $attributes = null;
    
    public function  __construct($query, $attributes)
    {
        $this->query = $query;
        $this->attributes = $attributes;
    }

    public function collection()
    {
        return $this->query;
    }

    public function map($row): array {
        $fields = [
            $row->title ?? "",
            $row->slug ?? "", //
            $row->category->title ?? "",
            $row->icons ?? "",
            $row->optional_icons ?? "",
            $row->sub_tags ?? "",
            $row->variant_name ?? "",
            $row->slug ?? "",
            $row->led_fitted ?? "",
            $row->co_related_color ?? "",
            $row->co_related_color_code ?? "",
            $row->lumens ?? "",
            $row->efficacy ?? "",
            $row->beam_angle ?? "",
            $row->beam_angle_code ?? "",
            $row->led_power_watts ?? "",
            $row->system_power_watts ?? "",
            $row->operating_voltage ?? "",
            $row->power_factor ?? ""
        ];

        if (isset($row->attribute_objects) && $row->attribute_objects != null) {
            foreach($this->attributes as $attribute) {
                if (isset($row->attribute_objects[$attribute->id]) && $row->attribute_objects[$attribute->id] != null) {
                    $fields[] = $row->attribute_objects[$attribute->id]->value;
                } else {
                    $fields[] = '';
                }
            }
        }
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
        $headings = [
          'Name',
          'Slug',
          'Category',
          'Icons',
          'Optional Icons',
          'Sub Tags',
          'Variant Name',
          'Variant Slug',
          'LED Fitted',
          'Co-Related Color Temprature Values',
          'Co-Related Color Temprature Codes',
          'Delivered Lumens',
          'Efficacy',
          'Beam Angle Values',
          'Beam Angle Codes',
          'LED Power Watts',
          'System Power Watts',
          'Operating Voltage VIN',
          'Power Factor P.F.'
        ];

        foreach($this->attributes as $attribute) {
            $headings[] = $attribute->attribute_name;
        }

        return $headings;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Products';
    }
}
