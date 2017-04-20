<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
  protected $fillable = [
    'name', 'description', 'thumbnail', 'source', 'tags',
  ];

  public function getTagsAttribute($value)
  {
    return json_decode($value, true);
  }

  public function setTagsAttribute($value)
  {
    $value = array_filter($value);
    $this->attributes['tags'] = json_encode($value);
  }
}
