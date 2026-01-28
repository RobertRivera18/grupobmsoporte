<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Documentos;
use App\Models\Ticket;
use App\Models\TicketDetalle;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TicketCreado;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('admin.tickets.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.tickets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */





    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'descripcion' => 'required|string',
            'documentos.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Crear el ticket
        $ticket = Ticket::create([
            'usu_id' => auth()->id(),
            'category_id' => $request->category_id,
            'tick_titulo' => $request->titulo,
            'tick_descrip' => $request->descripcion,
            'tick_estado' => 1,
            'est' => 1,
        ]);

        // Procesar documentos si existen
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $archivo) {
                $path = $archivo->store('documentos', 'public');
                Documentos::create([
                    'tick_id' => $ticket->tick_id,
                    'doc_nombre' => $path,
                ]);
            }
        }

        // Notificar a los administradores
        $admins = User::role('Admin')->get(); // Asegúrate de usar Spatie o un sistema de roles
        Notification::send($admins, new TicketCreado($ticket));

        // Alerta en la interfaz (SweetAlert)
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Ticket creado con éxito. Sistemas atenderá su ticket.',
            'position' => 'center',
            'toast' => true,
            'timer' => 5000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.tickets.index');
    }



    public function cambiarEstado(Ticket $ticket)
    {
        // Cambiar estado: 1 = Abierto, 2 = Cerrado
        $ticket->tick_estado = $ticket->tick_estado == 1 ? 2 : 1;
        $ticket->save();

        // Si se cerró el ticket (estado 2), registrar comentario automático
        if ($ticket->tick_estado == 2) {
            \App\Models\TicketDetalle::create([
                'tick_id' => $ticket->tick_id,
                'usu_id' => auth()->id(),
                'tickd_descrip' => 'Ticket cerrado.',
                'est' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Estado del ticket actualizado.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'tickd_descrip' => 'required',

        ]);
        TicketDetalle::create([
            'tick_id' => $ticket->tick_id,
            'usu_id' => auth()->id(),
            'tickd_descrip' => $request->tickd_descrip,
            'est' => 1,
        ]);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El comentario se agrego con exito.',
            'position' => 'center',
            'toast' => true,
            'timer' => 5000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.tickets.edit', $ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $uploadPath = public_path('uploads/tickets/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            return response()->json([
                'success' => true,
                'url' => asset('uploads/tickets/images/' . $imageName)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al subir la imagen'
        ], 400);
    }
}
