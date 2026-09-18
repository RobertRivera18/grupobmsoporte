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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Corregido el Driver
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketController extends Controller
{
    /**
     * Constructor para aplicar Rate Limit exclusivamente al almacenamiento de tickets.
     */
   public static function middleware(): array
    {
        return [
            new Middleware('throttle:3,1', only: ['store']),
        ];
    }

    public function index()
    {
        return view('admin.tickets.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'         => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'descripcion'    => 'required|string',
            'documentos.*'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:8192',
        ], [
            'titulo.required'       => 'El título del requerimiento es obligatorio.',
            'category_id.required'  => 'Debe seleccionar una categoría.',
            'descripcion.required'  => 'La descripción detallada es obligatoria.',
            'documentos.*.max'      => 'El tamaño del archivo no debe ser mayor a 8 MB.',
            'documentos.*.mimes'    => 'El formato del archivo no está permitido. Use JPG, PNG, PDF o DOC.',
        ]);

        $storedPaths = [];

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'usu_id'       => auth()->id(),
                'category_id'  => $data['category_id'],
                'tick_titulo'  => $data['titulo'],
                'tick_descrip' => $data['descripcion'],
                'tick_estado'  => 1,
                'est'          => 1,
            ]);

            if ($request->hasFile('documentos')) {
                $manager    = new ImageManager(new Driver());
                $imageMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

                foreach ($request->file('documentos') as $archivo) {
                    if (in_array($archivo->getMimeType(), $imageMimes)) {
                        $encoded = $manager->read($archivo->getRealPath())
                            ->scaleDown(width: 1600)
                            ->toWebp(75);

                        $filename = 'tickets/' . Str::uuid() . '.webp';
                        Storage::disk('public')->put($filename, (string) $encoded);
                        $path = $filename;

                        unset($encoded);
                    } else {
                        $path = $archivo->store('tickets', 'public');
                    }

                    $storedPaths[] = $path;

                    Documentos::create([
                        'tick_id'    => $ticket->tick_id,
                        'doc_nombre' => $path,
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($e);

            return back()->withInput()->with('swal', [
                'icon'  => 'error',
                'title' => 'Algo salió mal',
                'text'  => 'No se pudo crear el ticket. Intente nuevamente.',
            ]);
        }
        
        $ticket->load('category');
        $admins = User::role('Admin')->get();
        Notification::send($admins, new TicketCreado($ticket));

        session()->flash('swal', [
            'icon'              => 'success',
            'title'             => '¡Bien hecho!',
            'text'              => 'Ticket creado con éxito. Sistemas atenderá su solicitud.',
            'position'          => 'center',
            'toast'             => true,
            'timer'             => 5000,
            'showConfirmButton' => false,
        ]);
        
        return redirect()->route('admin.tickets.index');
    }

    public function cambiarEstado(Ticket $ticket)
    {
        $ticket->tick_estado = $ticket->tick_estado == 1 ? 2 : 1;
        $ticket->save();

        if ($ticket->tick_estado == 2) {
            TicketDetalle::create([
                'tick_id' => $ticket->tick_id,
                'usu_id' => auth()->id(),
                'tickd_descrip' => 'Ticket cerrado.',
                'est' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Estado del ticket actualizado.');
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
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
            'text' => 'El comentario se agregó con éxito.',
            'position' => 'center',
            'toast' => true,
            'timer' => 5000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.tickets.edit', $ticket);
    }

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