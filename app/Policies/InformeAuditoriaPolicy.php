<?php

namespace App\Policies;

use App\Models\AuditoriaProceso;
use App\Models\InformeAuditoria;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InformeAuditoriaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InformeAuditoria $informe): bool
    {

        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($user->hasRole('LiderAuditorias')) {
            return true;
        }
        return $informe->auditoriaProceso
            && $informe->auditoriaProceso->auditor_id === $user->id;
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
    public function update(User $user, InformeAuditoria $informeAuditoria): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InformeAuditoria $informeAuditoria): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InformeAuditoria $informeAuditoria): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InformeAuditoria $informeAuditoria): bool
    {
        return false;
    }
}
