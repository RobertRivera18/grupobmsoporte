<?php

namespace App\Policies;

use App\Models\SolicitudDesvinculacion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SolicitudDesvinculacionPolicy
{

    public function gestionarTalentoHumano(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Talento Humano', 'TalentoHumano']);
    }

    /**
     * Determina si el usuario puede gestionar la sección de Sistemas (Equipos).
     */
    public function gestionarSistemas(User $user): bool
    {
        return $user->hasAnyRole(['Admin']);
    }

    public function gestionarBodega(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Bodega']);
    }


    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SolicitudDesvinculacion $solicitud): bool
    {
        return $user->hasAnyRole(['Admin', 'Talento Humano', 'TalentoHumano']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SolicitudDesvinculacion $solicitudDesvinculacion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SolicitudDesvinculacion $solicitud): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SolicitudDesvinculacion $solicitudDesvinculacion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SolicitudDesvinculacion $solicitudDesvinculacion): bool
    {
        return false;
    }
}
