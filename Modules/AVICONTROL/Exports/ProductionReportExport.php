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

class ProductionReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, ShouldAutoSize
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
            'Fecha',
            'Tipo Producción', 
            'Galpón',
            'Tipo',
            'Cantidad',
            'Mortalidad Aves',
            'Huevos Buenos',
            'Huevos Rotos',
            'Huevos Sucios',
            'Porcentaje Producción',
            'Peso Promedio (kg)',
            'Peso Total (kg)',
            'Valor Unidad ($)',
            'Valor Total ($)',
            'Destino',
            'Semana Producción',
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
        return [
            Carbon::parse($row['fecha'])->format('d/m/Y'),
            ucfirst($row['tipo_produccion'] ?? 'huevos'),
            $row['galpon'] ?? 'N/A',
            $row['tipo'] ?? 'N/A',
            $row['cantidad'] ?? 0,
            $row['mortalidad_aves'] ?? 0,
            $row['huevos_buenos'] ?? 0,
            $row['huevos_rotos'] ?? 0,
            $row['huevos_sucios'] ?? 0,
            number_format($row['porcentaje'] ?? 0, 2) . '%',
            $row['peso_promedio'] ?? 0,
            $row['peso_total'] ?? 0,
            $row['valor_unidad'] ?? 0,
            $row['valor_total'] ?? 0,
            $row['destino'] ?? 'N/A',
            $row['semana_produccion'] ?? 'N/A',
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
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_PERCENTAGE_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'N' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
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
        $sheet->getStyle('A1:R' . $lastRow)->applyFromArray([
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
                $sheet->getStyle('A' . $i . ':R' . $i)->applyFromArray([
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
            
            $sheet->setCellValue('A' . $statsRow, 'ESTADÍSTICAS DE PRODUCCIÓN');
            $sheet->getStyle('A' . $statsRow . ':R' . $statsRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            $statsRow += 2;
            $stats = [
                ['Estadística', 'Valor'],
                ['Total Producción', number_format($this->estadisticas['total_produccion'] ?? 0)],
                ['Producción Hoy', number_format($this->estadisticas['produccion_hoy'] ?? 0)],
                ['Producción del Mes', number_format($this->estadisticas['produccion_mes'] ?? 0)],
                ['Promedio Diario', number_format($this->estadisticas['promedio_diario'] ?? 0)],
                ['Mejor Galpón', $this->estadisticas['mejor_galpon'] ?? 'N/A'],
                ['Aves Activas', number_format($this->estadisticas['total_aves_activas'] ?? 0)],
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
