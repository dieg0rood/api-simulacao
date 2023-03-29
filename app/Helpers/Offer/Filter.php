<?php

namespace App\Helpers\Offer;

class Filter
{
  public $offer;
  private $installments;
  private $value;
  private $interestRate;

  public function __construct($offer, $value, $installments)
  {
    $this->offer = $offer;
    $this->value = $value;
    $this->installments = $installments;
    $this->calcAmountToPay();
  }

  public function getOffer()
  {
    return $this->offer;
  }

  private function calcAmountToPay()
  {
    foreach ($this->offer as $key => $oneOffer) {
      $valorTotal = $this->calcularPrice($this->value, $oneOffer['jurosMes'], $this->installments);
       $this->offer[$key]['valorAPagar'] = $this->formatValueReal($valorTotal);
    }
  }

  function calcularPrice($capital, $taxa, $periodos) {
    $juros = (1 + $taxa) ** $periodos;
    $prestacao = ($capital * ($taxa * $juros)) / ($juros - 1);
    $saldo_devedor = $capital;
    $total_pago = 0;
    
    for ($i = 1; $i <= $periodos; $i++) {
        $juros_periodo = $saldo_devedor * $taxa;
        $amortizacao = $prestacao - $juros_periodo;
        $saldo_devedor -= $amortizacao;
        $total_pago += $prestacao;
    }
    
    return $total_pago;
}

  public function filterValue()
  {
    foreach ($this->offer as $key => $oneOffer) {
      if ($oneOffer['valorMin'] > $this->value || $oneOffer['valorMax'] < $this->value) {
        unset($this->offer[$key]);
      }
    }
    return $this;
  }

  public function filterInstallments()
  {
    foreach ($this->offer as $key => $oneOffer) {
      if ($oneOffer['QntParcelaMin'] > $this->installments || $oneOffer['QntParcelaMax'] < $this->installments) {
        unset($this->offer[$key]);
      }
    }
    return $this;
  }


  public function orderByMostAdvantageous()
  {
    usort($this->offer, function ($a, $b) {
      if ($a['valorAPagar'] == $b['valorAPagar']) {
        return 0;
      }
      return ($a['valorAPagar'] < $b['valorAPagar']) ? -1 : 1;
    });
    return $this;
  }

  public function formatValueReal($value)
  {
      $number = number_format($value, 2, ',', '.');
      return 'R$' . $number;
  }
}
