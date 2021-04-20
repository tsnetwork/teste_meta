<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Planta;

class Abelha extends Model 
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'especie',
    ];

    public function plantas(){
        return $this->belongsToMany(Planta::class, "planta_abelhas", 'id_abelha', 'id_planta');
      }
}
