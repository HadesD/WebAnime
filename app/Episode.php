<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
  protected $fillable = [
    'film_id', 'name', 'source',
  ];
}
