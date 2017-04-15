<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
  protected $fillable = [
    'name', 'description', 'thumbnail', 'source', 'tags',
  ];
}
