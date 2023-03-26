<?php

namespace App\Facades\ApiGosat\V1;
use Illuminate\Support\Facades\Facade;

class ConnectionApi extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'api-gosat-v1';
  }
}