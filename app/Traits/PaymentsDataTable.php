<?php

namespace App\Traits;

use App\Models\Enums\PaymentType;
use App\Utils\ActionButton;

trait PaymentsDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query): array
  {

    if (!is_null($this->searchConfig['search'])) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('events.customer', 'like', '%'. $search .'%')
        ->orWhere('payment_date', 'like', '%'. $search .'%')
        ->orWhere('events.center', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $payments = $query->limit($this->searchConfig['length'])->offset( $this->searchConfig['start'] )
      ->orderBy('payment_date', 'desc')->get();
    $data = [];
    foreach($payments as $payment)
      $data[] = $this->getDataRow($payment);
    return $this->getSearchResult($data);
  }

  private function getDataRow($payment): array
  {
    $editButton = ActionButton::getDefaultEditButton();
    $editButton->addAttribute('data-id', $payment->id);
    $editButton->setClick("editPayment(this)");

    $deleteButton = ActionButton::getDefaultDeleteButton();
    $deleteButton->addAttribute('data-id', $payment->id);
    $deleteButton->setClick("deletePayment(this)");

    return [
      'payment_date' => date('d-m-Y', strtotime($payment->payment_date)),
      'customer' => $payment->customer,
      'amount' => '$ '.number_format($payment->amount, 2),
      'voucher' => $payment->voucher,
      'payment_type' => PaymentType::memberByKey($payment->payment_type)->message(),
      'actions' => $editButton->getIconButton()."    ". $deleteButton->getIconButton()
    ];
  }

}