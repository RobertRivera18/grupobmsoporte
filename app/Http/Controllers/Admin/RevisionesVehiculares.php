<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehiculoInspeccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;

class RevisionesVehiculares extends Controller
{

    public function index()
    {
        return view('admin.revisiones.index');
    }
    public function show(VehiculoInspeccion $vehiculoinspeccion)
    {
        $vehiculoinspeccion->load('vehiculo', 'fotos');

        return view('admin.revisiones.show', compact('vehiculoinspeccion'));
    }

    public function exportWord(Request $request, VehiculoInspeccion $vehiculoinspeccion)
    {
        $vehiculoinspeccion->load(['vehiculo', 'tecnicoEncargado', 'fotos']);

        $filename = "FOR-MAN-05_Revision_" . ($vehiculoinspeccion->vehiculo->placa ?? $vehiculoinspeccion->id) . ".docx";

        $yaFirmado = $vehiculoinspeccion->documento_firmado_path
            && Storage::disk('public')->exists($vehiculoinspeccion->documento_firmado_path);

        if ($yaFirmado && !$request->boolean('regenerar')) {
            return Storage::disk('public')->download($vehiculoinspeccion->documento_firmado_path, $filename);
        }
        $request->validate([
            'firma_base64' => 'required|string',
        ]);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 1200,
            'marginBottom' => 1200,
            'marginLeft' => 1200,
            'marginRight' => 1200
        ]);

        $fontHeader = ['name' => 'Arial', 'size' => 11, 'bold' => true];
        $fontBody = ['name' => 'Arial', 'size' => 9];
        $fontBodyBold = ['name' => 'Arial', 'size' => 9, 'bold' => true];

        $tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80];
        $headerBg = ['bgColor' => 'F2F2F2'];
        $tableHeader = $section->addTable($tableStyle);
        $tableHeader->addRow();
        $tableHeader->addCell(2500)->addText("Grupo BM", $fontHeader, ['alignment' => Jc::CENTER]);
        $tableHeader->addCell(5500)->addText("DEPARTAMENTO DE MANTENIMIENTO\nINSPECCION DE VEHICULOS LIVIANOS", $fontHeader, ['alignment' => Jc::CENTER]);
        $tableHeader->addCell(2500)->addText("FOR-MAN-05\nRev: 02\nFecha: 25/10/2021", $fontBody, ['alignment' => Jc::CENTER]);

        $section->addTextBreak(1);

        $tableDatos = $section->addTable($tableStyle);
        $tableDatos->addRow();
        $tableDatos->addCell(1500, $headerBg)->addText("MODELO:", $fontBodyBold);
        $tableDatos->addCell(2500)->addText(($vehiculoinspeccion->vehiculo->marca ?? '') . ' ' . ($vehiculoinspeccion->vehiculo->modelo ?? ''), $fontBody);
        $tableDatos->addCell(1200, $headerBg)->addText("PLACA:", $fontBodyBold);
        $tableDatos->addCell(1800)->addText($vehiculoinspeccion->vehiculo->placa ?? 'S/P', $fontBody);
        $tableDatos->addCell(1000, $headerBg)->addText("KMS:", $fontBodyBold);
        $tableDatos->addCell(2500)->addText(number_format($vehiculoinspeccion->kilometraje ?? 0) . ' km', $fontBody);

        $tableDatos->addRow();
        $tableDatos->addCell(1500, $headerBg)->addText("TÉCNICO:", $fontBodyBold);
        $tableDatos->addCell(4000, ['gridSpan' => 3])->addText($vehiculoinspeccion->tecnicoEncargado->name ?? 'No registrado', $fontBody);
        $tableDatos->addCell(1000, $headerBg)->addText("EQUIPO:", $fontBodyBold);
        $tableDatos->addCell(2500)->addText(strtoupper($vehiculoinspeccion->tipo_equipo ?? ''), $fontBody);

        $section->addTextBreak(1);
        $section->addText("INSPECCION DEL VEHICULO", $fontHeader, ['alignment' => Jc::CENTER]);
        $tableChecklist = $section->addTable($tableStyle);

        $tableChecklist->addRow();
        for ($i = 0; $i < 3; $i++) {
            $tableChecklist->addCell(2600, $headerBg)->addText("DESCRIPCIÓN", $fontBodyBold);
            $tableChecklist->addCell(900, $headerBg)->addText("ESTADO", $fontBodyBold, ['alignment' => Jc::CENTER]);
        }

        $flatItems = [];
        if (!empty($vehiculoinspeccion->checklist)) {
            foreach ($vehiculoinspeccion->checklist as $categoria => $items) {
                foreach ($items as $item => $estado) {
                    $flatItems[] = [
                        'label' => str_replace('_', ' ', strtoupper($item)),
                        'status' => $estado ? 'OK' : 'NO OK'
                    ];
                }
            }
        }

        $chunks = array_chunk($flatItems, 3);
        foreach ($chunks as $chunk) {
            $tableChecklist->addRow();
            for ($i = 0; $i < 3; $i++) {
                if (isset($chunk[$i])) {
                    $tableChecklist->addCell(2600)->addText($chunk[$i]['label'], $fontBody);
                    $tableChecklist->addCell(900)->addText($chunk[$i]['status'], $fontBodyBold, ['alignment' => Jc::CENTER]);
                } else {
                    $tableChecklist->addCell(2600)->addText('', $fontBody);
                    $tableChecklist->addCell(900)->addText('', $fontBody);
                }
            }
        }

        $section->addTextBreak(1);
        $tableObs = $section->addTable($tableStyle);
        $tableObs->addRow();
        $tableObs->addCell(10500, $headerBg)->addText("CHOQUES Y GOLPES", $fontBodyBold);
        $tableObs->addRow();
        $tableObs->addCell(10500)->addText($vehiculoinspeccion->choques_golpes ?? 'Ninguno reportado.', $fontBody);

        $tableObs->addRow();
        $tableObs->addCell(10500, $headerBg)->addText("OBSERVACIONES GENERALES", $fontBodyBold);
        $tableObs->addRow();
        $tableObs->addCell(10500)->addText($vehiculoinspeccion->observaciones ?? 'Sin observaciones adicionales.', $fontBody);

        $tableObs->addRow();
        $tableObs->addCell(10500, $headerBg)->addText("RECOMENDACIONES DE MANTENIMIENTO", $fontBodyBold);
        $tableObs->addRow();
        $tableObs->addCell(10500)->addText($vehiculoinspeccion->recomendaciones_mantenimiento ?? 'No se prescribieron acciones inmediatas.', $fontBody);

        $section->addTextBreak(1);
        if ($vehiculoinspeccion->fotos && $vehiculoinspeccion->fotos->isNotEmpty()) {
            $section->addText("EVIDENCIAS FOTOGRÁFICAS LEVANTADAS", $fontHeader);
            $section->addTextBreak(1);
            $tableFotos = $section->addTable(['borderSize' => 0, 'cellMargin' => 100]);
            foreach ($vehiculoinspeccion->fotos->chunk(2) as $rowFotos) {
                $tableFotos->addRow();
                foreach ($rowFotos as $foto) {
                    $pathFoto = storage_path('app/public/' . $foto->ruta_foto);
                    $cellFoto = $tableFotos->addCell(5250);

                    if (file_exists($pathFoto)) {
                        $cellFoto->addImage($pathFoto, [
                            'width'         => 240,
                            'height'        => 160,
                            'marginTop'     => 5,
                            'marginBottom'  => 5,
                            'alignment'     => Jc::CENTER
                        ]);
                    } else {
                        $cellFoto->addText("[Imagen no disponible en el servidor]", ['name' => 'Arial', 'size' => 8, 'italic' => true]);
                    }
                }
            }
            $section->addTextBreak(1);
        }

        $firmaPathRelativo = null;
        $firmaAbsoluta = null;
        $firmaBase64 = $request->input('firma_base64');

        if ($firmaBase64) {
            try {
                $imgData = str_replace('data:image/png;base64,', '', $firmaBase64);
                $imgData = str_replace(' ', '+', $imgData);
                $firmaBinaria = base64_decode($imgData);

                if ($firmaBinaria) {
                    $firmaPathRelativo = 'firmas/firma_' . $vehiculoinspeccion->id . '_' . uniqid() . '.png';
                    Storage::disk('public')->put($firmaPathRelativo, $firmaBinaria);
                    $firmaAbsoluta = storage_path('app/public/' . $firmaPathRelativo);
                }
            } catch (\Exception $e) {
                $firmaPathRelativo = null;
                $firmaAbsoluta = null;
            }
        }

        $tableFirmas = $section->addTable(['borderSize' => 0, 'cellMargin' => 100]);
        $tableFirmas->addRow(1200);
        $cellResp = $tableFirmas->addCell(5250);
        $cellResp->addTextBreak(3);
        $cellResp->addText("___________________________", $fontBody, ['alignment' => Jc::CENTER]);
        $cellResp->addText("REVISADO POR (RESP. MANTENIMIENTO)", $fontBodyBold, ['alignment' => Jc::CENTER]);
        $cellResp->addText("Nombre: " . ($vehiculoinspeccion->revisado_por_nombre ?? ''), $fontBody, ['alignment' => Jc::CENTER]);
        $cellTecnico = $tableFirmas->addCell(5250);

        if ($firmaAbsoluta && file_exists($firmaAbsoluta) && filesize($firmaAbsoluta) > 0) {
            $textRunFirma = $cellTecnico->addTextRun(['alignment' => Jc::CENTER]);
            $textRunFirma->addImage($firmaAbsoluta, [
                'width'           => 140,
                'height'          => 70,
                'wrappingStyle'   => 'inline',
                'marginTop'       => 0,
                'marginBottom'    => 5
            ]);
            $cellTecnico->addTextBreak(1);
        } else {
            $cellTecnico->addTextBreak(3);
            $cellTecnico->addText("___________________________", $fontBody, ['alignment' => Jc::CENTER]);
        }

        $cellTecnico->addText("TÉCNICO RESPONSABLE", $fontBodyBold, ['alignment' => Jc::CENTER]);
        $cellTecnico->addText(($vehiculoinspeccion->tecnicoEncargado->name ?? ''), $fontBody, ['alignment' => Jc::CENTER]);

        $comprobantePathRelativo = 'comprobantes/inspeccion_' . $vehiculoinspeccion->id . '_' . time() . '.docx';
        $comprobanteAbsoluta = storage_path('app/public/' . $comprobantePathRelativo);

        if (!is_dir(dirname($comprobanteAbsoluta))) {
            mkdir(dirname($comprobanteAbsoluta), 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($comprobanteAbsoluta);

        if ($vehiculoinspeccion->documento_firmado_path && Storage::disk('public')->exists($vehiculoinspeccion->documento_firmado_path)) {
            Storage::disk('public')->delete($vehiculoinspeccion->documento_firmado_path);
        }
        if ($vehiculoinspeccion->firma_path && Storage::disk('public')->exists($vehiculoinspeccion->firma_path)) {
            Storage::disk('public')->delete($vehiculoinspeccion->firma_path);
        }

        $vehiculoinspeccion->update([
            'firma_path' => $firmaPathRelativo,
            'documento_firmado_path' => $comprobantePathRelativo,
            'firmado_at' => now(),
        ]);

        return Storage::disk('public')->download($comprobantePathRelativo, $filename);
    }
}
