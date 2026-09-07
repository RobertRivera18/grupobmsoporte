<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use App\Models\Vehiculo;
use App\Models\Chofer;
use App\Models\IncidenteFoto;
use App\Models\MovimientoVehiculo;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class IncidenteVehiculoController extends Controller
{

    public function index()
    {
        $incidentes = Incidente::with('vehiculo')->orderBy('id', 'desc')->get();
        $rows = $incidentes->map(function ($incidente) {
            $vehiculo = $incidente->vehiculo;
            $fecha = \Carbon\Carbon::parse($incidente->fecha);
            return [
                'id'        => $incidente->id,
                'placa'     => $vehiculo->placa ?? 'S/P',
                'vehiculo'  => trim(($vehiculo->marca ?? '') . ' ' . ($vehiculo->modelo ?? '')) ?: 'Sin datos',
                'tipo'      => (int) $incidente->tipo,
                'fecha_ts'  => $fecha->timestamp,
                'fecha_fmt' => $fecha->format('d/m/Y h:i A'),
                'show_url'  => route('incidentesvehiculos.show', $incidente),
                'edit_url'  => route('incidentesvehiculos.edit', $incidente),
            ];
        })->values();

        return view('incidentesvehiculos.index', compact('incidentes', 'rows'));
    }


    public function create()
    {
        $choferes = User::role('Chofer')
            ->select('id', 'name')
            ->get();
        return view('incidentesvehiculos.create', compact('choferes'));
    }

    public function store(Request $request)
    {
        $ultimoKilometraje = MovimientoVehiculo::where('vehiculo_id', $request->vehiculo_id)
            ->orderByDesc('id', 'desc')
            ->value('kilometraje') ?? 0;

        $maxPermitido = $ultimoKilometraje + 80;
        $request->validate([
            'vehiculo_id'   => 'required|exists:vehiculos,id',
            'kilometraje'   => [
                'required',
                'numeric',
                'integer',
                "min:{$ultimoKilometraje}",
                "max:{$maxPermitido}",
            ],
            'tipo'          => 'required|in:1,2',
            'choferes'      => 'required|array|min:1',
            'choferes.*'    => 'exists:users,id',
            'observaciones' => 'nullable|string|max:1000',
            'fotos'         => 'nullable|array',
            'fotos.*'       => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'vehiculo_id.required' => 'Debe seleccionar un vehículo válido usando el buscador.',
            'kilometraje.min'      => "El kilometraje no puede ser menor al último registrado ({$ultimoKilometraje} km).",
            'kilometraje.max'      => "El kilometraje no puede superar {$maxPermitido} km. Si es correcto, contacte al administrador.",
            'choferes.required'    => 'Debe asignar al menos un chofer para este movimiento.',
            'fotos.*.image'        => 'Cada archivo debe ser una imagen válida.',
            'fotos.*.max'          => 'Cada imagen no debe superar los 5 MB.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $tipoTexto = ($request->tipo == 1) ? 'INGRESO' : 'SALIDA';

                $incidente = Incidente::create([
                    'vehiculo_id'   => $request->vehiculo_id,
                    'tipo'          => $request->tipo,
                    'observaciones' => $request->observaciones ?? 'S/N',
                ]);

                MovimientoVehiculo::create([
                    'vehiculo_id'   => $request->vehiculo_id,
                    'tipo'          => $tipoTexto,
                    'kilometraje'   => $request->kilometraje,
                    'observaciones' => $request->observaciones ?? 'S/N',
                ]);

                $incidente->choferes()->attach($request->choferes);
                if ($request->hasFile('fotos')) {
                    $manager = new ImageManager(new Driver());
                    $registros = [];

                    foreach ($request->file('fotos') as $foto) {
                        $img = $manager->read($foto->getRealPath());

                        if ($img->width() > 1200) {
                            $img->scale(width: 1200);
                        }

                        $nombreArchivo = 'incidentes/fotos/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($nombreArchivo, $img->toJpeg(75));

                        $registros[] = [
                            'incidente_id' => $incidente->id,
                            'archivo'      => $nombreArchivo,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ];
                    }
                    IncidenteFoto::insert($registros);
                }
            });


            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'El registro se ha creado con éxito',
                'position' => 'top-end',
                'toast' => true,
                'timer' => 3000,
                'showConfirmButton' => false
            ]);
            return redirect()->route('incidentesvehiculos.index');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('swal', [
                    'icon'              => 'error',
                    'title'             => 'Error al guardar',
                    'text'              => 'Ocurrió un problema inesperado. Intente nuevamente.',
                    'confirmButtonColor' => '#dc2626',
                    'confirmButtonText' => 'Cerrar',
                ]);
        }
    }

    public function edit(Incidente $incidente)
    {
        return view('incidentesvehiculos.edit', compact('incidente'));
    }

    public function show(Incidente $incidentesvehiculo)
    {
        $incidente = $incidentesvehiculo;
        $incidente->load(['vehiculo', 'choferes', 'fotos']);
        $movimiento = MovimientoVehiculo::where('vehiculo_id', $incidente->vehiculo_id)
            ->where('fecha', $incidente->fecha)
            ->first();
        $kilometraje = $movimiento ? number_format($movimiento->kilometraje, 0, ',', '.') . ' km' : 'S/R';

        return view('incidentesvehiculos.show', compact('incidente', 'kilometraje'));
    }
}
