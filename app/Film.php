<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
  protected $hidden = [
    'source', 'updated_at', 'created_at',
  ];

  protected $fillable = [
    'name', 'description', 'thumbnail', 'source', 'tags',
  ];

  public $appends = [
    'slug', 'views',
  ];

  /**
   * Get all Episodes of film by column film_id in episodes table
   * @return Object List Episodes
   */
  public function episodes()
  {
    return $this->hasMany('App\Episode');
  }

  public function tags()
  {
    $tags = json_decode($this->attributes['tags'], true);
    return Tag::findMany($tags);
  }

  public function getRoute()
  {
    return route('watch.film', [
      'film_id' => $this->attributes['id'],
      'film_slug' => $this->getSlugAttribute(),
    ]);
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

  public function getSlugAttribute()
  {
    return str_slug($this->attributes['name'], '-');
  }

  public function getViewsAttribute()
  {
    return number_format($this->episodes()->sum('views'));
  }
  /*
  public function getThumbnailAttribute($value)
  {
    if (empty($value) === true)
    {
      $value = '//i.imgur.com/IoQdToB.png';
    }

    return $value;
  }
  */
}
