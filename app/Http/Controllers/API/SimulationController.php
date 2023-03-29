<?php

namespace App\Http\Controllers\API;

use App\Facades\ApiGosat\V1\Endpoints\SimulationFacade;
use App\Helpers\Offer\Filter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
 *      path="/simulation",
 *      operationId="getSimulationList",
 *      tags={"Simulation"},
 *      summary="Returns a list of credit offers",
 *      description="Returns 3 credit offers ordered from the most advantageous to the least advantageous, based on the amount to pay",
 *      @OA\Parameter(
 *          name="cpf",
 *          in="query",
 *          description="CPF number",
 *          required=true,
 *          example="123.456.789-00",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="valor",
 *          in="query",
 *          description="Value requested for credit",
 *          required=true,
 *          example="5000",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="parcelas",
 *          in="query",
 *          description="Number of installments for credit",
 *          required=true,
 *          example="12",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *       ),
 *      @OA\Response(
 *          response=442,
 *          description="Unprocessable",
 *      )
 * )
 */

    private string $cpf;
    private int $parcelas;
    private string $valor;
    private Collection $instituicoes;
    private array $allOffers;
    public function show(Request $request)
    {
        $this->setValue($request->input('valor'));
        $this->setInstallments($request->input('parcelas'));
        $this->setDocument($request->input('cpf'));
        $this->setInstitutions(collect(SimulationFacade::credit($this->getDocument())));
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
        $offersFilter = (new Filter($this->getAllOffers(),$this->getValue(),$this->getInstallments()));
        $offers = $offersFilter->filterValue()
                    ->filterInstallments()
                    ->orderByMostAdvantageous()
                    ->getOffer();
        foreach ($offers as $offer) {
            $response[] = [
                'instituicaoFinanceira' => $offer["instituicaoFinanceira"],
                'modalidadeCredito' => $offer["modalidadeCredito"],
                'valorAPagar' => $offer['valorAPagar'],
                'valorSolicitado' => $offersFilter->formatValueReal($this->getValue()),
                'taxaJuros' => $offer["jurosMes"] * 100 . '% Mês',
                'qntParcelas' => $this->getInstallments(),
            ];
        }
        return $response;
    }

    private function setDocument($cpf)
    {
        $this->cpf = $cpf;
    }

    private function getDocument()
    {
        return $this->cpf;
    }

    private function setValue($valor)
    {
        $this->valor = $valor;
    }

    private function getValue()
    {
        return $this->valor;
    }

    private function setInstallments($parcelas)
    {
        $this->parcelas = $parcelas;
    }

    private function getInstallments()
    {
        return $this->parcelas;
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

}
