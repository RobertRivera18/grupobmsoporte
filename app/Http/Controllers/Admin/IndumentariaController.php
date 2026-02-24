<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indumentaria;
use App\Models\InventarioIndumentaria;
use App\Models\InventarioIndumentariaUsada;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndumentariaController extends Controller
{
    public function index()
    {
        return view('admin.indumentarias.index', [
            'empleados' => User::orderBy('name')->get(),
            'ubicaciones' => Ubicacion::orderBy('nombre')->get(),
            'indumentarias' => Indumentaria::orderBy('nombre')->get(),
        ]);
    }
    public function create()
    {
        $ubicaciones = Ubicacion::all();
        return view('admin.indumentarias.create', compact('ubicaciones'));
    }



    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'color' => 'nullable|string',
            'talla' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ubicaciones' => 'required|array',
            'ubicaciones.*' => 'exists:ubicaciones,id',
        ];

        if ($user->hasRole('Admin')) {
            $rules['stock_nuevo'] = 'nullable|array';
            $rules['stock_usado'] = 'nullable|array';
        }

        $request->validate($rules);

        // Guardar imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('indumentarias', 'public');
        }

        // Crear indumentaria
        $indumentaria = Indumentaria::create([
            'nombre' => $request->nombre,
            'tipo'   => $request->tipo,
            'color'  => $request->color,
            'talla'  => $request->talla,
            'image'  => $imagePath,
        ]);

        // Crear inventario por bodega
        foreach ($request->ubicaciones as $ubicacionId) {

            if ($user->hasRole('Admin')) {

                $nuevo = $request->stock_nuevo[$ubicacionId] ?? 0;
                $usado = $request->stock_usado[$ubicacionId] ?? 0;

                if ($nuevo > 0) {
                    InventarioIndumentaria::create([
                        'indumentaria_id' => $indumentaria->id,
                        'ubicacion_id'    => $ubicacionId,
                        'stock'           => $nuevo,
                    ]);
                }

                if ($usado > 0) {
                    InventarioIndumentariaUsada::create([
                        'indumentaria_id' => $indumentaria->id,
                        'ubicacion_id'    => $ubicacionId,
                        'stock'           => $usado,
                    ]);
                }
            } else {

                // Usuario normal → solo nuevo con stock 0
                InventarioIndumentaria::create([
                    'indumentaria_id' => $indumentaria->id,
                    'ubicacion_id'    => $ubicacionId,
                    'stock'           => 0,
                ]);
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Indumentaria creada con éxito',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.indumentarias.index');
    }
    public function edit(Indumentaria $indumentaria)
    {
        $indumentaria->load([
            'inventarios',
            'inventariosUsados'
        ]);
        $ubicaciones = Ubicacion::orderBy('nombre')->get();

        return view('admin.indumentarias.edit', compact('indumentaria', 'ubicaciones'));
    }


    public function update(Request $request, Indumentaria $indumentaria)
    {
        $user = auth()->user();

        $rules = [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'color' => 'nullable|string',
            'talla' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ubicaciones' => 'required|array',
            'ubicaciones.*' => 'exists:ubicaciones,id',
        ];

        if ($user->hasRole('Admin')) {
            $rules['stock_nuevo'] = 'nullable|array';
            $rules['stock_usado'] = 'nullable|array';
        }

        $request->validate($rules);

        /*
    |--------------------------------------------------------------------------
    | 📸 1️⃣ MANEJO DE IMAGEN
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('image')) {

            // Eliminar imagen anterior
            if ($indumentaria->image && file_exists(public_path($indumentaria->image))) {
                unlink(public_path($indumentaria->image));
            }

            $image = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('indumentarias'), $fileName);

            $indumentaria->image = 'indumentarias/' . $fileName;
        }

        /*
    |--------------------------------------------------------------------------
    | 👕 2️⃣ ACTUALIZAR DATOS
    |--------------------------------------------------------------------------
    */

        $indumentaria->update([
            'nombre' => $request->nombre,
            'tipo'   => $request->tipo,
            'color'  => $request->color,
            'talla'  => $request->talla,
            'image'  => $indumentaria->image,
        ]);

        /*
    |--------------------------------------------------------------------------
    | 📦 3️⃣ SINCRONIZAR INVENTARIO
    |--------------------------------------------------------------------------
    */

        // Eliminar inventarios que ya no estén seleccionados
        InventarioIndumentaria::where('indumentaria_id', $indumentaria->id)
            ->whereNotIn('ubicacion_id', $request->ubicaciones)
            ->delete();

        InventarioIndumentariaUsada::where('indumentaria_id', $indumentaria->id)
            ->whereNotIn('ubicacion_id', $request->ubicaciones)
            ->delete();

        foreach ($request->ubicaciones as $ubicacionId) {

            if ($user->hasRole('Admin')) {

                $nuevo = $request->stock_nuevo[$ubicacionId] ?? 0;
                $usado = $request->stock_usado[$ubicacionId] ?? 0;

                InventarioIndumentaria::updateOrCreate(
                    [
                        'indumentaria_id' => $indumentaria->id,
                        'ubicacion_id'    => $ubicacionId,
                    ],
                    [
                        'stock' => $nuevo,
                    ]
                );

                InventarioIndumentariaUsada::updateOrCreate(
                    [
                        'indumentaria_id' => $indumentaria->id,
                        'ubicacion_id'    => $ubicacionId,
                    ],
                    [
                        'stock' => $usado,
                    ]
                );
            } else {

                InventarioIndumentaria::updateOrCreate(
                    [
                        'indumentaria_id' => $indumentaria->id,
                        'ubicacion_id'    => $ubicacionId,
                    ],
                    [
                        'stock' => 0,
                    ]
                );
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Indumentaria actualizada con éxito',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.indumentarias.index');
    }

    public function destroy(Indumentaria $indumentaria)
    {
        $indumentaria->delete();
    }
}
