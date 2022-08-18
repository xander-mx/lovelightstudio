<?php

namespace App\Traits;

use App\Models\Enums\PaymentType;
use App\Utils\ActionButton;

trait EventDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('customer', 'like', '%'. $search .'%')
        ->orWhere('service', 'like', '%'. $search .'%')
        ->orWhere('center', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $events = $query->limit($this->searchConfig['length'])->offset( $this->searchConfig['start'] )
      ->orderBy('event_date', 'desc')->get();
    $data = [];
    foreach($events as $event)
      $data[] = $this->getDataRow($event);
    return $this->getSearchResult($data);
  }

  private function getDataRow($event): array
  {
    $showPaymentsButton = ActionButton::getDefaultButton('bi bi-file-earmark-text', 'btn-dark'
      , 'Mostrar Pagos');
    $showPaymentsButton->addAttribute('data-id', $event->id);
    $showPaymentsButton->addAttribute('data-customer', $event->customer);
    $showPaymentsButton->setClick("infoPayment(this)");

    $paymentButton = ActionButton::getDefaultButton('bi bi-cash', 'btn-info', 'Agregar Pago');
    $paymentButton->addAttribute('data-id', $event->id);
    $paymentButton->setClick("addPayment(this)");

    $editButton = ActionButton::getDefaultEditButton();
    $editButton->addAttribute('data-id', $event->id);
    $editButton->setClick("editEvent(this)");

    $deleteButton = ActionButton::getDefaultDeleteButton();
    $deleteButton->addAttribute('data-id', $event->id);
    $deleteButton->setClick("deleteEvent(this)");
    $finalButtons = ($event->status != 'LIQUIDADO') ? $editButton->getIconButton()."    ".$deleteButton->getIconButton()
      . "    ". $paymentButton->getIconButton() : '';
    $finalButtons .= "    ". $showPaymentsButton->getIconButton();

    return [
      'event_date' => date('d-m-Y', strtotime($event->event_date)),
      'customer' => $event->customer,
      'center' => $event->center,
      'service' => $event->service,
      'email' => $event->email,
      'phone' => $event->phone,
      'price' => '$ '.number_format($event->price,2),
      'balance' => '$ '.number_format($event->balance,2),
      'actions' => $finalButtons
    ];
  }

  protected function getSearchDataResultPayments($query): array
  {
    $this->searchConfig['recordsFiltered'] = $query->count();
    $payments = $query->take($this->searchConfig['length'])->skip($this->searchConfig['start'] )
      ->sortByDesc('payment_date');
    foreach ($payments as $payment) {
      $data[] = [
        'payment_date' => date('d-m-Y', strtotime($payment->payment_date)),
        'amount' => '$ '.number_format($payment->amount, 2),
        'voucher' => $payment->voucher,
        'payment_type' => PaymentType::memberByKey($payment->payment_type)->message(),
      ];
    }
    return $this->getSearchResult($data);
  }

}