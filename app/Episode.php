<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
  protected $fillable = [
    'film_id', 'name', 'source',
  ];

  public $appends = ['slug', 'ordinal'];

  public function getSourceAttribute($value)
  {
    return base64_encode($value);
  }

  public function getSlugAttribute($value)
  {
    return str_slug($this->attributes['name'], '-');
  }

  public function getOrdinalAttribute($value)
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
