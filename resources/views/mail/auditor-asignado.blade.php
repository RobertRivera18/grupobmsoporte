<x-mail::message>
# 📋 {{ $titulo }}

Estimado(a) **{{ $notifiable->name }}**,

Se han actualizado las asignaciones del sistema de control interno para los módulos de auditoría e indicadores de **Grupo BM**.

<x-mail::panel>

## 🔍 Resumen del Proceso de Auditoría

**Área Evaluada:** {{ $area }}

**Auditor Principal:** {{ $auditor }}

**Normas / Procesos a Fiscalizar:** 
{!! $normas ? nl2br(e($normas)) : 'No se registraron normas.' !!}

**Período Planificado:** {{ $fecha_inicio }} al {{ $fecha_fin }}

**Detalles de la asignación:** {{ $descripcion }}

</x-mail::panel>

<x-mail::button :url="$url" color="success">
Ver Panel de Auditoría
</x-mail::button>

Por favor, asegúrese de contar con toda la documentación de soporte requerida y sus respectivos indicadores KPI actualizados antes de la fecha de inicio programada.

Atentamente,

**Departamento de TICs Procesos de Gestion de Calidad IS090001** **Grupo BM**
</x-mail::message>