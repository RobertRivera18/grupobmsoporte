<?php

namespace App\Rules;

use App\Models\InventarioControl;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UnicoReporteCuadrillaDia implements ValidationRule
{
   public function __construct(
        protected $tecnologiaId,
        protected $fechaInventario
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || !$this->tecnologiaId || !$this->fechaInventario) {
            return;
        }

        $existe = InventarioControl::where('cuadrilla_id', $value)
            ->where('tecnologia_id', $this->tecnologiaId)
            ->whereDate('fecha_inventario', $this->fechaInventario)
            ->exists();

        if ($existe) {
            $fail('La cuadrilla seleccionada ya registró un reporte hoy para esta tecnología.');
        }
    }
}
