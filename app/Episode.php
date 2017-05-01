<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
  protected $hidden = [
    'source', 'updated_at', 'created_at',
  ];

  protected $fillable = [
    'film_id', 'name', 'source',
  ];

  public $appends = [
    'slug', 'ordinal',
  ];

  public function film()
  {
    return $this->belongsTo('App\Film');
  }

  public function getRoute()
  {
    return route('watch.episode', [
      'film_id' => $this->film->id,
      'film_slug' => $this->film->getSlugAttribute(),
      'episode_id' => $this->attributes['id'],
      'episode_slug' => $this->getSlugAttribute(),
    ]);
  }

  public function getSlugAttribute()
  {
    return str_slug($this->attributes['name'], '-');
  }

  public function getOrdinalAttribute()
  {
    $name = $this->attributes['name'];

    preg_match('/([0-9]+)/i', $name, $order);

    if (isset($order[1]) === false)
    {
      return;
    }

    return intval($order[1]);
  }
}
