<?php

namespace App\Facades\ApiGosat\V1\Endpoints;

use App\Facades\ApiGosat\V1\ConnectionApi;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Facade;

class SimulationFacade extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'Simulation';
  }

  public static function credit(string $cpf)
  {
    try {
      $response = ConnectionApi::post('/simulacao/credito', [
        'cpf' => $cpf,
      ]);
      if ($response->getStatusCode() !== 200) {
        throw new \App\Exceptions\DocumentException($response->getBody(), $response->getStatusCode());
      }
    } catch (RequestException $e) {
      $response = $e->getResponse();
      $errorMessage = $response->getBody()->getContents();
      dd($errorMessage);
    }
    return $response->json()['instituicoes'];
  }

  public static function offer(string $codModalidade, int $instituicao_id, string $cpf)
  {
    try {
      $response = ConnectionApi::post('/simulacao/oferta', [
        'cpf' => $cpf,
        'instituicao_id' => $instituicao_id,
        'codModalidade' => $codModalidade,  
      ]);
      if ($response->getStatusCode() !== 200) {
        throw new \App\Exceptions\OfferException($response->getBody(), $response->getStatusCode());
      }
    } catch (RequestException $e) {
      $response = $e->getResponse();
      $errorMessage = $response->getBody()->getContents();
      dd($errorMessage);
    }
    return $response->json();
  }
}
