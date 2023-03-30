<?php

namespace App\Business\API;

use App\Business\API\Simulation\Api;
use App\Business\API\Simulation\Database;
use App\Helpers\FormatValues;

class Simulation
{
    public function getFromApi($document, $value, $installments, $simulationId)
    {
        return (new Api())->getFromApi($document, $value, $installments, $simulationId);
    }

    public function getFromDatabase($simulationId, $value, $installments)
    {
        return (new Database())->getFromDatabase($simulationId, $value, $installments);
    }

    public function hasOffersInDatabase($simulationId)
    {
        return (new Database())->hasOffersInDatabase($simulationId);
    }

    public function getSimulationId($document, $value, $installments)
    {
        return (new Database())->getSimulationId($document, $value, $installments);
    }
    protected function getResponse($offers, $value, $installments)
    {
        $response = [];
        foreach ($offers as $offer) {
            $response[] = [
                'instituicaoFinanceira' => $offer["instituicaoFinanceira"],
                'modalidadeCredito' => $offer["modalidadeCredito"],
                'valorAPagar' => (new FormatValues())::formatValueReal($offer['valorAPagar']),
                'valorSolicitado' => (new FormatValues())::formatValueReal($value),
                'taxaJuros' => $offer["jurosMes"] * 100 . '% Mês',
                'qntParcelas' => intval($installments),
            ];
        }
        return $response;
    }
}
