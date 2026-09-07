<?php

namespace App\Enums;

enum QuestionType: string
{
    case SINGLE_CHOICE = 'single_choice';

    case MULTIPLE_CHOICE = 'multiple_choice';

    case TRUE_FALSE = 'true_false';

    case SHORT_ANSWER = 'short_answer';

    case LONG_ANSWER = 'long_answer';

    case NUMERIC = 'numeric';

    public function label(): string
    {
        return match ($this) {

            self::SINGLE_CHOICE =>
                'Selección única',

            self::MULTIPLE_CHOICE =>
                'Selección múltiple',

            self::TRUE_FALSE =>
                'Verdadero / Falso',

            self::SHORT_ANSWER =>
                'Respuesta corta',

            self::LONG_ANSWER =>
                'Respuesta abierta',

            self::NUMERIC =>
                'Respuesta numérica',
        };
    }
}