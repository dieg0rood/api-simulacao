<?php

namespace App\Http\Controllers\API;

use App\Facades\ApiGosat\V1\Endpoints\SimulationFacade;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

/**
 * @OA\Info(
 *     title="Credit Simulation API",
 *     version="1.0.0",
 *     description="Teste GoSat"
 * )
 */

class SimulationController extends Controller
{
    /**
     * @OA\Get(
     *      path="/simulation/{document}",
     *      operationId="getSimulationList",
     *      tags={"Simulation"},
     *      summary="Returns a list of credit offers",
     *      description="Returns 3 credit offers ordered from the most advantageous to the least advantageous, based on the interest rate",
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *       ),
     *      @OA\Response(
     *          response=442,
     *          description="Unprocessable",
     *      )
     *     )
     */
    private string $cpf;
    private Collection $instituicoes;
    private array $allOffers;
    public function show($cpf)
    {
        $this->setDocument($cpf);
        $this->setInstitutions(collect(SimulationFacade::credit($cpf)));
        $this->setAllOffers($this->getInstitutions());
        return response()->json($this->getResponse());
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

    private function getResponse()
    {
        $response = [];
        $offers = $this->orderByJuros($this->getAllOffers());
        foreach ($offers as $offer) {
            $response[] = [
                'instituicaoFinanceira' => $offer["instituicaoFinanceira"],
                'modalidadeCredito' => $offer["modalidadeCredito"],
                'valorAPagar' => '-',
                'valorSolicitado' => $this->formatValueReal($offer["valorMin"]) . ' até ' . $this->formatValueReal($offer["valorMax"]),
                'taxaJuros' => $offer["jurosMes"] * 100 . '% Mês',
                'qntParcelas' => $offer["QntParcelaMin"] . ' até ' . $offer["QntParcelaMax"],
            ];
        }
        return $response;
    }

    private function orderByJuros($offers)
    {
        usort($offers, function ($a, $b) {
            if ($a['jurosMes'] == $b['jurosMes']) {
                return 0;
            }
            return ($a['jurosMes'] < $b['jurosMes']) ? -1 : 1;
        });
        return $offers;
    }

    private function setDocument($cpf)
    {
        $this->cpf = $cpf;
    }

    private function getDocument()
    {
        return $this->cpf;
    }

    private function setInstitutions(Collection $instituicoes)
    {
        $this->instituicoes = $instituicoes;
    }

    private function getInstitutions()
    {
        return $this->instituicoes;
    }

    private function setAllOffers(Collection $creditSimulation)
    {
        $creditSimulation->map(function ($institutions) {
            foreach ($institutions['modalidades'] as $modalidade) {
                $offer = SimulationFacade::offer(
                    $modalidade['cod'],
                    $institutions['id'],
                    $this->getDocument()
                );
                $this->allOffers[] = $this->returnAll($offer, $modalidade, $institutions);
            }
        });
    }

    private function getAllOffers()
    {
        return $this->allOffers;
    }

    private function formatValueReal($value)
    {
        $number = number_format($value, 2, ',', '.');
        return 'R$' . $number;
    }
}
