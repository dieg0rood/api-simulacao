<?php

namespace App\Helpers;

class FormatValues
{
  public static function formatValueReal($value)
  {
      $number = number_format($value, 2, ',', '.');
      return 'R$' . $number;
  }
}