<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitaPropiedadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "persona" => new PersonaResource($this->persona),
            "propiedad" => new PropiedadResource($this->propiedad),
            "fecha_visita" => $this->fecha_visita,
            "comentarios" => $this->comentarios
        ];
    }
}
