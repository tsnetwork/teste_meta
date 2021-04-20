<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Abelha;
class Planta extends Model 
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'especie','desc', 'meses_florada'
    ];

    public function abelhas(){
      return $this->belongsToMany(Abelha::class, "planta_abelhas", 'id_planta','id_abelha');
    }
}
