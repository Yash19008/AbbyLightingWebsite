<?php

namespace App\Exports;

use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportInquiry implements FromCollection, WithHeadings, WithStyles, WithMapping, WithTitle, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection(): Enumerable
    {
        return $this->query instanceof Enumerable ? $this->query : collect($this->query);
    }

    private function getPageName(?string $type): string
    {
        $map = [
            'general' => 'Contact Us',
            'calculator' => 'Light Calculator',
            'catalogue' => 'Catalogues',
            'product' => 'Product',
        ];
        return $map[strtolower($type ?? '')] ?? ucfirst(str_replace(['_', '-'], ' ', $type ?? 'General'));
    }

    public function map(mixed $row): array
    {
        return [
            $row->id,
            $this->getPageName($row->type),
            $row->reference ?: '-',
            $row->name ?: '',
            $row->email ?: '',
            $row->phone ?: '',
            $row->city ?: '',
            $row->company ?: '',
            $row->role ?: '',
            $row->message ?: '',
            $row->created_at ? $row->created_at->format('M d, Y h:i A') : '',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B']
                ]
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '# ID',
            'Page',
            'Reference',
            'Name',
            'Email',
            'Mobile',
            'City',
            'Company',
            'Role',
            'Message',
            'Date Received',
        ];
    }

    public function title(): string
    {
        return 'Inquiries';
    }
}
