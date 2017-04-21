<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
  protected $fillable = [
    'name', 'description', 'thumbnail', 'source', 'tags',
  ];

  /**
   * Get all Episodes of film by column film_id in episodes table
   * @return Object List Episodes
   */
  public function episodes()
  {
    return $this->hasMany('App\Episode');
  }

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
