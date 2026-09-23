<?php

namespace App\Enums;

enum TipoIncidencia: string
{
    case ROBO_CREDENCIAL = 'Robo Credencial';
    case PERDIDA_CREDENCIAL = 'Pérdida Credencial';
    case RENOVACION_CREDENCIAL = 'Renovación Credencial';

    public function label(): string
    {
        return match($this) {
            self::ROBO_CREDENCIAL => 'Robo Credencial',
            self::PERDIDA_CREDENCIAL => 'Pérdida Credencial',
            self::RENOVACION_CREDENCIAL => 'Renovación Credencial',
        };
    }
}