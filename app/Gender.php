<?php

namespace App;

enum Gender: string
{
    case Male = 'Male';
    case Female = 'Female';

    public function label()
    {
        return match ($this) {
            self::Male => self::Male,
            self::Female => self::Female,
        };
    }
}
