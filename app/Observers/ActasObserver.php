<?php

namespace App\Observers;

use App\Models\Actas;
use Illuminate\Support\Facades\Storage;

class ActasObserver
{
    /**
     * Handle the Actas "created" event.
     */
    public function created(Actas $actas): void
    {
        //
    }

    /**
     * Handle the Actas "updated" event.
     */
    public function updated(Actas $actas): void
    {
        //
    }

    /**
     * Handle the Actas "deleted" event.
     */
    public function deleted(Actas $actas): void
    {
        //
    }

    /**
     * Handle the Actas "restored" event.
     */
    public function restored(Actas $actas): void
    {
        //
    }

    /**
     * Handle the Actas "force deleted" event.
     */
    public function forceDeleted(Actas $actas): void
    {
        //
    }
    public function deleting(Actas $acta)
    {
        // Eliminar imagen principal
        if ($acta->imagen_path && Storage::disk('public')->exists($acta->imagen_path)) {
            Storage::disk('public')->delete($acta->imagen_path);
        }

        // Eliminar archivo firmado si existe
        if ($acta->ruta_firma && Storage::disk('public')->exists($acta->ruta_firma)) {
            Storage::disk('public')->delete($acta->ruta_firma);
        }
    }
}
