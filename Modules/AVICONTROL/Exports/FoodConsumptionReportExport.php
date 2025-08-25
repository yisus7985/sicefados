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

class FoodConsumptionReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, ShouldAutoSize
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
            'Producto',
            'Cantidad (kg)',
            'Cantidad Bultos',
            'Peso por Bulto (kg)',
            'Número de Aves',
            'Consumo por Ave (g)',
            'Costo Total ($)',
            'Costo por kg ($)',
            'Responsable',
            'Estado',
            'Observaciones'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $consumoPorAve = 0;
        if (isset($row['numero_aves']) && $row['numero_aves'] > 0) {
            $consumoPorAve = ($row['cantidad_kg'] ?? 0) / $row['numero_aves'] * 1000; // Convertir a gramos
        }

        $costoTotal = ($row['cantidad_kg'] ?? 0) * ($row['precio_por_kg'] ?? 0);
        
        return [
            Carbon::parse($row['fecha_registro'])->format('d/m/Y'),
            $row['galpon_nombre'] ?? 'N/A',
            $row['producto_nombre'] ?? 'N/A',
            $row['cantidad_kg'] ?? 0,
            $row['cantidad_bultos'] ?? 0,
            $row['peso_por_bulto'] ?? 0,
            $row['numero_aves'] ?? 0,
            round($consumoPorAve, 2),
            $costoTotal,
            $row['precio_por_kg'] ?? 0,
            $row['responsable'] ?? 'N/A',
            ucfirst($row['estado'] ?? 'activo'),
            $row['observaciones'] ?? 'Sin observaciones'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'J' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28a745'], // Verde para alimentos
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
        $sheet->getStyle('A1:M' . $lastRow)->applyFromArray([
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
                $sheet->getStyle('A' . $i . ':M' . $i)->applyFromArray([
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
            
            $sheet->setCellValue('A' . $statsRow, 'ESTADÍSTICAS DE CONSUMO DE ALIMENTOS');
            $sheet->getStyle('A' . $statsRow . ':M' . $statsRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '28a745'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            $statsRow += 2;
            $stats = [
                ['Estadística', 'Valor'],
                ['Total Gastado', '$' . number_format($this->estadisticas['total_gasto'] ?? 0, 2)],
                ['Promedio por Galpón', '$' . number_format($this->estadisticas['promedio_galpon'] ?? 0, 2)],
                ['Total Kilos Consumidos', number_format($this->estadisticas['total_kilos'] ?? 0) . ' kg'],
                ['Promedio Diario', number_format($this->estadisticas['promedio_diario'] ?? 0, 2) . ' kg'],
                ['Mejor Galpón', $this->estadisticas['mejor_galpon'] ?? 'N/A'],
                ['Producto Más Consumido', $this->estadisticas['producto_mas_consumido'] ?? 'N/A'],
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
