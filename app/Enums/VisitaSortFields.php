<?php

namespace App\Enums;

enum VisitaSortFields : string {

    case ID = "id";
    case FECHA = "fecha_visita";
    case PROPIEDAD = "propiedad_id";
    case PERSONA = "persona_id";
}
