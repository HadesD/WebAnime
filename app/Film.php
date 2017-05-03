<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
  protected $table = 'films';

  protected $hidden = [
    'source', 'updated_at', 'created_at',
  ];

  protected $fillable = [
    'name', 'description', 'thumbnail', 'source',
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
    return $this->hasMany('App\FilmEpisode');
  }

  public function tags()
  {
    return $this->belongsToMany('App\Tag');
  }

  public function getRoute()
  {
    return route('watch.film', [
      'film_id' => $this->attributes['id'],
      'film_slug' => $this->getSlugAttribute(),
    ]);
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
