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

class ProductionCostReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, ShouldAutoSize
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
            'ID',
            'Fecha Registro',
            'Galpón',
            'Lote/Código',
            'Período Inicio',
            'Período Fin',
            'Tipo de Costo',
            'Categoría',
            'Descripción',
            'Costo Total ($)',
            'Costo por Unidad ($)',
            'Tipo de Unidad',
            'Cantidad',
            'Estado',
            'Responsable',
            'Observaciones'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $fechaRegistro = isset($row['created_at']) ? Carbon::parse($row['created_at']) : null;
        $periodoInicio = isset($row['period_start']) ? Carbon::parse($row['period_start']) : null;
        $periodoFin = isset($row['period_end']) ? Carbon::parse($row['period_end']) : null;

        // Determinar el tipo de costo legible
        $tiposCosto = [
            'batch' => 'Por Lote',
            'monthly' => 'Mensual',
            'weekly' => 'Semanal',
            'daily' => 'Diario',
            'feed' => 'Alimentación',
            'medical' => 'Médico',
            'maintenance' => 'Mantenimiento'
        ];

        $tipoCosto = $tiposCosto[$row['cost_type'] ?? ''] ?? ucfirst($row['cost_type'] ?? 'N/A');

        // Determinar estado legible
        $estados = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'completed' => 'Completado',
            'pending' => 'Pendiente'
        ];

        $estado = $estados[$row['status'] ?? 'active'] ?? ucfirst($row['status'] ?? 'Activo');
        
        return [
            $row['id'] ?? 'N/A',
            $fechaRegistro ? $fechaRegistro->format('d/m/Y H:i') : 'N/A',
            $row['facility_name'] ?? 'N/A',
            $row['batch_code'] ?? 'N/A',
            $periodoInicio ? $periodoInicio->format('d/m/Y') : 'N/A',
            $periodoFin ? $periodoFin->format('d/m/Y') : 'N/A',
            $tipoCosto,
            $row['cost_category'] ?? 'General',
            $row['description'] ?? 'Sin descripción',
            $row['total_cost'] ?? 0,
            $row['cost_per_unit'] ?? 0,
            $row['unit_type_name'] ?? $row['unit_type'] ?? 'unidad',
            $row['quantity'] ?? 0,
            $estado,
            $row['responsible'] ?? 'N/A',
            $row['notes'] ?? $row['observations'] ?? 'Sin observaciones'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'K' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'M' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'dc3545'], // Rojo para costos
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
        $sheet->getStyle('A1:P' . $lastRow)->applyFromArray([
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
                $sheet->getStyle('A' . $i . ':P' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                ]);
            }
        }

        // Resaltar columnas de costos
        $sheet->getStyle('J2:K' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '155724'],
            ],
        ]);

        // Agregar información de estadísticas al final
        if (!empty($this->estadisticas)) {
            $statsRow = $lastRow + 3;
            
            $sheet->setCellValue('A' . $statsRow, 'ESTADÍSTICAS DE COSTOS DE PRODUCCIÓN');
            $sheet->getStyle('A' . $statsRow . ':P' . $statsRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => 'dc3545'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            $statsRow += 2;
            $stats = [
                ['Estadística', 'Valor'],
                ['Total de Costos', '$' . number_format($this->estadisticas['total_costos'] ?? 0, 2)],
                ['Costo Promedio', '$' . number_format($this->estadisticas['costo_promedio'] ?? 0, 2)],
                ['Costo Más Alto', '$' . number_format($this->estadisticas['costo_mas_alto'] ?? 0, 2)],
                ['Costo Más Bajo', '$' . number_format($this->estadisticas['costo_mas_bajo'] ?? 0, 2)],
                ['Total de Registros', number_format($this->estadisticas['total_registros'] ?? 0)],
                ['Galpón con Mayor Costo', $this->estadisticas['galpon_mayor_costo'] ?? 'N/A'],
                ['Tipo de Costo Más Común', $this->estadisticas['tipo_mas_comun'] ?? 'N/A'],
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
