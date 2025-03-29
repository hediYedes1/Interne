<?php

namespace App\Enum;

enum Role: string
{
    case CANDIDAT = 'CANDIDAT';
    case RH = 'RH';
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';
}