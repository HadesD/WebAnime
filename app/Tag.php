<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  public $timestamps = false;

  protected $fillable = [
      'name',
  ];

  public $appends = [
    'slug',
  ];

  public function getSlugAttribute($value)
  {
    return str_slug($this->attributes['name'], '-');
  }
}
