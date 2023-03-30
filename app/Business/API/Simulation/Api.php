<?php

namespace App\Business\API\Simulation;

use App\Business\API\simulation;
use App\Facades\ApiGosat\V1\Endpoints\SimulationFacade;
use App\Helpers\Offer\Filter;
use App\Models\Offer;

class Api extends Simulation
{
  public function getFromApi($document, $value, $installments, $simulationId)
  {
    $institutions = SimulationFacade::credit($document);
    $allOffersApi = $this->getOffersFromApi($institutions, $document);

    $offersFilter = (new Filter(
      $allOffersApi,
      $value,
      $installments
    ));
    
    $offers = $offersFilter->filterValue()
      ->filterInstallments()
      ->orderByMostAdvantageous()
      ->getOffer();
    
    if(empty($offers))
    {
      throw new \App\Exceptions\NoResultException('Não existem propostas disponíveis para os dados informados.',422);
    }

    $this->saveOffers($offers, $simulationId, $installments);
    return $this->getResponse($offers, $value, $installments);
  }

  private function getOffersFromApi(array $creditSimulation, $document)
  {
    $allOffersApi = array();
    foreach($creditSimulation as $institutions) {
      foreach ($institutions['modalidades'] as $modalidade) {
        $offer = SimulationFacade::offer(
          $modalidade['cod'],
          $institutions['id'],
          $document
        );
        $allOffersApi[] = $this->returnAll($offer, $modalidade, $institutions);
      }
    };
    return $allOffersApi;
  }

  private function returnAll($offer, $modalidade, $institution)
  {
    return [
      "idInstituicaoFinanceira" => $institution["id"],
      "codModalidadeCredito" => $modalidade["cod"],
      "instituicaoFinanceira" => $institution["nome"],
      "modalidadeCredito" => $modalidade["nome"],
      "QntParcelaMin" => $offer["QntParcelaMin"],
      "QntParcelaMax" => $offer["QntParcelaMax"],
      "valorMin" => $offer["valorMin"],
      "valorMax" => $offer["valorMax"],
      "jurosMes" => $offer["jurosMes"],
    ];
  }

  private function saveOffers($offers, $simulationId, $installments)
  {
    foreach ($offers as $offer) {
      Offer::create([
        'simulation_id' => $simulationId,
        'instituicaoFinanceira' => $offer['instituicaoFinanceira'],
        'modalidadeCredito' => $offer['modalidadeCredito'],
        'valorAPagar' => sprintf('%.2f',number_format($offer['valorAPagar'], 2, '.', '')),
        'taxaJuros' => $offer['jurosMes'],
        'qntParcelas' => $installments
      ]);
    }
  }
}
