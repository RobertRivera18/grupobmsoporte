@component('mail::message')
# Nuevo Ticket Creado

Hola **{{ $notifiable->name }}**,

El usuario **{{ $usuario->name }}** ha creado un nuevo ticket en el sistema.

### Detalles del Ticket:
- **Título:** {{ $ticket->tick_titulo }}
- **Categoría:** {{ $ticket->categoria->name ?? 'N/A' }}
- **Descripción:**  
{{ $ticket->tick_descrip }}

@component('mail::button', ['url' => route('admin.tickets.show', $ticket->tick_id)])
Ver Ticket
@endcomponent

Gracias por su atención.  
**GrupoBM**
@endcomponent
