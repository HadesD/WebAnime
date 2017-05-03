<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  protected $table = 'tags';

  public $timestamps = false;

  protected $fillable = [
      'name',
  ];

  public $appends = [
    'slug',
  ];

  public function films()
  {
    return $this->belongsToMany('App\Film');
  }

  public function getSlugAttribute($value)
  {
    return str_slug($this->attributes['name'], '-');
  }
}
