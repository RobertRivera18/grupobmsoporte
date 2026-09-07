@component('mail::message')
# 🎫 Nuevo Ticket de Soporte

Hola **{{ $notifiable->name }}**,

Se ha registrado un nuevo ticket en la plataforma de soporte de **Grupo BM**. A continuación, encontrarás los detalles de la solicitud:

---

### 📋 Detalles del Caso

* **Título del Ticket:** {{ $ticket->tick_titulo }}
* **Categoría Asignada:** {{ $ticket->categoria->name ?? 'No asignada' }}
* **Creado por:** {{ $usuario->name }}

**Descripción del problema:** <div>{!! $ticket->tick_descrip !!}</div>

---

@component('mail::button', ['url' => route('admin.tickets.show', $ticket->tick_id), 'color' => 'success'])
Revisar y Gestionar Ticket
@endcomponent

Si requieres asistencia técnica adicional o necesitas verificar el historial, puedes ingresar directamente a la consola de administración.

Atentamente,  
**Equipo Técnico | Grupo BM**
@endcomponent