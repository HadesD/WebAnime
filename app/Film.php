<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
  protected $fillable = [
    'name', 'description', 'thumbnail', 'source', 'tags',
  ];

  public function getTagsAttribute()
  {
    return json_decode($this->attributes['tags'], true);
  }

  public function setTagsAttribute($value)
  {
    $this->attributes['tags'] = json_encode($value);
  }
}
