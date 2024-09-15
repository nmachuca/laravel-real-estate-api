<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaPropiedad extends Model
{
    use HasFactory;

    protected $table = 'visita_propiedades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'persona_id',
        'propiedad_id',
        'fecha_visita',
    ];

    public function persona() {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function propiedad() {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
