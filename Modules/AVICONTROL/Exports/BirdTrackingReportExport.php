<?php

namespace Modules\AVICONTROL\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class BirdTrackingReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $estadisticas;

    public function __construct($data, $estadisticas = [])
    {
        $this->data = collect($data);
        $this->estadisticas = $estadisticas;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Fecha Registro',
            'Galpón',
            'Raza',
            'Cantidad',
            'Peso Promedio (g)',
            'Peso Total (kg)',
            'Edad (días)',
            'Estado',
            'Tasa Mortalidad (%)',
            'Conversión Alimenticia',
            'Costo por Ave ($)',
            'Valor Total ($)',
            'Observaciones',
            'Responsable'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $fechaRegistro = isset($row['created_at']) ? Carbon::parse($row['created_at']) : null;
        $fechaNacimiento = isset($row['birth_date']) ? Carbon::parse($row['birth_date']) : null;
        
        $edad = 0;
        if ($fechaNacimiento && $fechaRegistro) {
            $edad = $fechaNacimiento->diffInDays($fechaRegistro);
        }

        $tasaMortalidad = 0;
        if (isset($row['initial_quantity']) && $row['initial_quantity'] > 0) {
            $muertos = ($row['initial_quantity'] ?? 0) - ($row['quantity'] ?? 0);
            $tasaMortalidad = ($muertos / $row['initial_quantity']) * 100;
        }

        $costoTotal = ($row['quantity'] ?? 0) * ($row['cost_per_bird'] ?? 0);
        
        return [
            $fechaRegistro ? $fechaRegistro->format('d/m/Y') : 'N/A',
            $row['galpon_nombre'] ?? 'N/A',
            $row['breed'] ?? 'N/A',
            $row['quantity'] ?? 0,
            $row['weight'] ?? 0,
            ($row['weight'] ?? 0) * ($row['quantity'] ?? 0) / 1000, // Convertir a kg
            $edad,
            ucfirst($row['status'] ?? 'active'),
            round($tasaMortalidad, 2),
            $row['feed_conversion'] ?? 0,
            $row['cost_per_bird'] ?? 0,
            $costoTotal,
            $row['observations'] ?? 'Sin observaciones',
            $row['responsible'] ?? 'N/A'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_PERCENTAGE_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'L' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007bff'], // Azul para seguimientos
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Altura de la fila de encabezado
        $sheet->getRowDimension('1')->setRowHeight(25);

        // Bordes para toda la tabla
        $lastRow = $this->data->count() + 1;
        $sheet->getStyle('A1:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Alternar colores de filas
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':N' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                ]);
            }
        }

        // Agregar información de estadísticas al final
        if (!empty($this->estadisticas)) {
            $statsRow = $lastRow + 3;
            
            $sheet->setCellValue('A' . $statsRow, 'ESTADÍSTICAS DE SEGUIMIENTO DE AVES');
            $sheet->getStyle('A' . $statsRow . ':N' . $statsRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '007bff'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            $statsRow += 2;
            $stats = [
                ['Estadística', 'Valor'],
                ['Total Aves en Seguimiento', number_format($this->estadisticas['total_aves_seguimiento'] ?? 0)],
                ['Peso Promedio Actual', number_format($this->estadisticas['promedio_peso'] ?? 0) . ' g'],
                ['Edad Promedio', number_format($this->estadisticas['edad_promedio'] ?? 0) . ' días'],
                ['Tasa de Crecimiento', number_format($this->estadisticas['tasa_crecimiento'] ?? 0, 1) . '%'],
                ['Mejor Galpón', $this->estadisticas['mejor_galpon'] ?? 'N/A'],
                ['Conversión Alimenticia Promedio', number_format($this->estadisticas['conversion_promedio'] ?? 0, 2)],
            ];

            foreach ($stats as $index => $stat) {
                $currentRow = $statsRow + $index;
                $sheet->setCellValue('A' . $currentRow, $stat[0]);
                $sheet->setCellValue('B' . $currentRow, $stat[1]);
                
                if ($index === 0) {
                    // Encabezado de estadísticas
                    $sheet->getStyle('A' . $currentRow . ':B' . $currentRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E7E6E6'],
                        ],
                    ]);
                }
            }
        }

        return [];
    }
}
