<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
  protected $fillable = [
    'film_id', 'name', 'source',
  ];
  
  public function getSourceAttribute($value)
  {
    return base64_encode($value);
  }
}
