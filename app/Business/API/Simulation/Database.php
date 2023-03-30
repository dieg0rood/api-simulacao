<?php

namespace App\Business\API\Simulation;

use App\Business\API\Simulation as SimulationBusiness;
use App\Models\Offer;
use App\Models\Simulation;

class Database extends SimulationBusiness
{

  public function getFromDatabase($simulationId, $value, $installments)
  {
    $offers = $this->getOffersFromDatabase($simulationId);
    return $this->getResponse($offers, $value, $installments);
  }

  private function getOffersFromDatabase($simulationId)
  {
    $allOffersDatabase = array();
    $offersSaved = Offer::where('simulation_id', $simulationId)->orderBy('valorAPagar','asc')->get();
    foreach ($offersSaved as $offer) {
      $allOffersDatabase[] = [
        'instituicaoFinanceira' => $offer->instituicaoFinanceira,
        'modalidadeCredito' => $offer->modalidadeCredito,
        'valorAPagar' => $offer->valorAPagar,
        'jurosMes' => $offer->taxaJuros,
      ];
    };
    return $allOffersDatabase;
  }

  public function hasOffersInDatabase($simulationId)
  {
    return Offer::where('simulation_id', $simulationId)->count() > 0;
  }

  public function getSimulationId($document, $value, $installments)
    {
        $simulation = Simulation::where('cpf', $document)
            ->where('parcelas', $installments)
            ->where('valorSolicitado', $value)
            ->first();
        if (is_null($simulation)) {
            $simulation = Simulation::create([
                'cpf' => $document,
                'parcelas' => $installments,
                'valorSolicitado' => sprintf('%.2f',number_format($value, 2, '.', '')),
            ]);
        }
        return $simulation->id;
    }
}
