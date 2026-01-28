<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indumentaria;
use App\Models\InventarioIndumentaria;
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'color' => 'nullable|string',
            'talla' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ubicaciones' => 'required|array',
            'ubicaciones.*' => 'exists:ubicaciones,id',
        ]);

        // 📸 1️⃣ Guardar imagen (si existe)
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('indumentarias', 'public');
        }

        // 👕 2️⃣ Crear indumentaria
        $indumentaria = Indumentaria::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'color' => $request->color,
            'talla' => $request->talla,
            'image' => $imagePath,
        ]);

        // 📦 3️⃣ Crear inventario por bodega (stock = 0)
        foreach ($request->ubicaciones as $ubicacionId) {
            InventarioIndumentaria::create([
                'indumentaria_id' => $indumentaria->id,
                'ubicacion_id' => $ubicacionId,
                'stock' => 0,
            ]);
        }

        // 🔔 4️⃣ Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Indumentaria creada con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.indumentarias.index');
    }
    public function edit(Indumentaria $indumentaria)
    {
        $indumentaria->load('inventarios');
        $ubicaciones = Ubicacion::orderBy('nombre')->get();

        return view('admin.indumentarias.edit', compact('indumentaria', 'ubicaciones'));
    }


    public function update(Request $request, Indumentaria $indumentaria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'color' => 'nullable|string',
            'talla' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ubicaciones' => 'required|array',
            'ubicaciones.*' => 'exists:ubicaciones,id',
        ]);

        // 📸 Imagen
        if ($request->hasFile('image')) {
            if ($indumentaria->image) {
                Storage::disk('public')->delete($indumentaria->image);
            }
            $indumentaria->image = $request->file('image')->store('indumentarias', 'public');
        }

        // 👕 Actualizar datos
        $indumentaria->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'color' => $request->color,
            'talla' => $request->talla,
        ]);

        // 📦 Sincronizar bodegas (NO duplica)
        InventarioIndumentaria::where('indumentaria_id', $indumentaria->id)
            ->whereNotIn('ubicacion_id', $request->ubicaciones)
            ->delete();

        foreach ($request->ubicaciones as $ubicacionId) {
            InventarioIndumentaria::firstOrCreate([
                'indumentaria_id' => $indumentaria->id,
                'ubicacion_id' => $ubicacionId,
            ], [
                'stock' => 0,
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Indumentaria actualizada con éxito',
            'toast' => true,
            'position' => 'top-end',
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
