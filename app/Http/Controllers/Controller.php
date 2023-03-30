<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $business;

    public function __construct()
    {
        $class = str_replace('Http\Controllers', 'Business', get_class($this));
        $class = str_replace('Controller', '', $class);
        if (class_exists($class)) {
            $this->business = new $class();
        };
    }
}
