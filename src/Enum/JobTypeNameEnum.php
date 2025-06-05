<?php

namespace App\Enum;

enum JobTypeNameEnum: string
{
    case TEMPS_PLEIN = 'Temps plein';
    case TEMPS_PARTIEL = 'Temps partiel';
    case STAGE = 'Stage';
    case ALTERNANCE = 'Alternance';
    case INTERIM = 'Intérim';
    case FREELANCE = 'Freelance';
}