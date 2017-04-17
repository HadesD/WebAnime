<?php

namespace App\Http\Controllers\API\Watch\GetLink;

use Illuminate\Http\Request;
use App\Http\Controllers\API\Watch\GetLinkController;

class Anime47Com extends GetLinkController
{
  protected function canGetLink()
  {
    parent::canGetLink();

    return true;
  }
}
