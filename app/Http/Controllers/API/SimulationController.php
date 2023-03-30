<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Simulation;
use Illuminate\Http\JsonResponse;


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

    public function __construct(private Simulation $simulacao, private Offer $oferta)
    {
        parent::__construct();
    }
    public function show(Request $request): JsonResponse
    {
        $value = $request->input('valor');
        $installments = $request->input('parcelas');
        $document = $request->input('cpf');
        $simulationId = $this->business->getSimulationId($document, $value, $installments);

        if ($this->business->hasOffersInDatabase($simulationId)) {
            $databaseData = $this->business->getFromDatabase($simulationId,$value,$installments);
            return response()->json($databaseData);
        }

        $apiData = $this->business->getFromApi($document,$value,$installments,$simulationId);
        return response()->json($apiData);
    }  
}
