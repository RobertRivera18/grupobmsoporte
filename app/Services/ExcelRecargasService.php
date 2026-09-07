<?php

namespace App\Services;

use App\Models\Cuadrilla;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelRecargasService
{
    private const VALOR_RECARGA = 10.50;

    public function generar()
    {
        $cuadrillas = $this->obtenerCuadrillas();

        $spreadsheet = $this->cargarPlantilla();

        $sheet = $spreadsheet->getActiveSheet();

        $this->generarEncabezado($sheet);

        $cantidadCuadrillas = $this->llenarDetalle(
            $sheet,
            $cuadrillas
        );

        $this->generarTotales(
            $sheet,
            $cantidadCuadrillas
        );

        return $this->guardarExcel($spreadsheet);
    }

    private function obtenerCuadrillas()
    {
        return Cuadrilla::with([
            'equipos' => fn($q) => $q->where('tipo_equipo_id', 4),
            'users'
        ])
            ->whereHas(
                'equipos',
                fn($q) => $q->where('tipo_equipo_id', 4)
            )
            ->get();
    }

    private function cargarPlantilla(): Spreadsheet
    {
        $rutaPlantilla = public_path(
            'templates/formatoListadoRecargas.xlsx'
        );

        return IOFactory::load($rutaPlantilla);
    }

    private function generarEncabezado($sheet): void
    {
        $fechaInicio = now()
            ->setDate(now()->year, now()->month, 23);

        $fechaFin = (clone $fechaInicio)
            ->addMonth();

        $encabezado =
            'LISTADO DE LINEAS DE RECARGAS MENSUALES PERIODO '
            . $fechaInicio->format('d/m/Y')
            . ' AL '
            . $fechaFin->format('d/m/Y');

        $sheet->mergeCells('C4:I7');

        $sheet->setCellValue(
            'C4',
            $encabezado
        );

        $sheet->getStyle('C4')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('C4')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            )
            ->setVertical(
                Alignment::VERTICAL_CENTER
            );
    }

    private function llenarDetalle($sheet, $cuadrillas): int
    {
        $fila = 9;
        $contador = 1;
        $cantidadCuadrillas = 0;

        foreach ($cuadrillas as $cuadrilla) {

            $inicioFila = $fila;

            $ciudad = $this->obtenerCiudad(
                $cuadrilla->cua_ciudad
            );

            $serie = $cuadrilla->equipos
                ->first()
                ?->serie ?? 'Sin serie';

            $estadoRecarga =
                $cuadrilla->recargas == 1
                ? 'Recarga Realizada'
                : 'Recarga No Realizada';

            $colaboradores =
                $cuadrilla->users->isNotEmpty()
                ? $cuadrilla->users
                : collect([null]);

            foreach ($colaboradores as $colaborador) {

                $sheet->setCellValue(
                    'C' . $fila,
                    $contador
                );

                $sheet->setCellValue(
                    'D' . $fila,
                    $ciudad
                );

                $sheet->setCellValue(
                    'E' . $fila,
                    $serie
                );

                $sheet->setCellValue(
                    'F' . $fila,
                    $cuadrilla->cua_nombre
                );

                $sheet->setCellValue(
                    'G' . $fila,
                    $colaborador->name ?? 'Sin colaboradores'
                );

                $sheet->setCellValue(
                    'H' . $fila,
                    self::VALOR_RECARGA
                );

                $sheet->setCellValue(
                    'I' . $fila,
                    $estadoRecarga
                );

                $fila++;
            }

            $finFila = $fila - 1;

            if ($inicioFila < $finFila) {

                foreach (
                    ['C', 'D', 'E', 'F', 'H', 'I']
                    as $columna
                ) {
                    $sheet->mergeCells(
                        "{$columna}{$inicioFila}:{$columna}{$finFila}"
                    );
                }
            }

            $contador++;
            $cantidadCuadrillas++;
        }

        return $cantidadCuadrillas;
    }

    private function generarTotales(
        $sheet,
        int $cantidadCuadrillas
    ): void {

        $total =
            $cantidadCuadrillas *
            self::VALOR_RECARGA;

        $sheet->setCellValue(
            'H77',
            '$' . number_format($total, 2)
        );
    }

    private function guardarExcel(
        Spreadsheet $spreadsheet
    ) {
        $nombreArchivo =
            'reporteRecargas_'
            . now()->format('Y-m-d_His')
            . '.xlsx';

        $directorio =
            storage_path('app/public/temp');

        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $rutaArchivo =
            $directorio
            . '/'
            . $nombreArchivo;

        $writer = new Xlsx($spreadsheet);

        $writer->save($rutaArchivo);

        return response()->download(
            $rutaArchivo,
            $nombreArchivo,
            [
                'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
        )->deleteFileAfterSend(true);
    }

    private function obtenerCiudad(
        ?int $codigo
    ): string {
        return match ($codigo) {
            1 => 'Guayaquil',
            2 => 'Quito',
            default => 'Sin ciudad',
        };
    }
}