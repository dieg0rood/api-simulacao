<?php

namespace App\Exceptions;

use Exception;

class DocumentException extends Exception
{
  public function __construct($message, $code)
  {
    if (empty($message)) {
      $message = 'Erro Desconhecido';
    }
    parent::__construct('Erro na requisição - ' . json_decode($message), $code);
  }
}
