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
        ->orWhere('coordinator', 'like', '%'. $search .'%')
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
    switch($event->status) {
      case 'LIQUIDADO':
        $finalButtons = $this->generateButtonsToSoldOut($event->id)."    ".$this->generateButtonsFinalize($event);
        break;
      case 'FINALIZADO':
        $finalButtons = $this->generateButtonsFinalize($event);
        break;
      default:
        $finalButtons = $this->generateButtonsNotSoldOut($event);
    }

    return [
      'event_date' => date('d-m-Y', strtotime($event->event_date)),
      'customer' => $event->customer,
      'center' => $event->center,
      'service' => $event->service,
      'email' => $event->email,
      'coordinator' => $event->coordinator,
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

  private function generateButtonsToSoldOut(int $id): string
  {
    $emailButton = ActionButton::getDefaultButton(' bi-envelope-paper-heart', 'btn-info', 'Enviar fotos');
    $emailButton->addAttribute('data-id', $id);
    $emailButton->setClick("captureLinks(this, false)");

    $videoButton = ActionButton::getDefaultButton(' bi-camera-video', 'btn-esmerald', 'Enviar Video');
    $videoButton->addAttribute('data-id', $id);
    $videoButton->setClick("captureLinks(this, true)");

    $finalizeButton = ActionButton::getDefaultButton('bi-check-square', 'btn-success', 'Cerrar Evento');
    $finalizeButton->addAttribute('data-id', $id);
    $finalizeButton->setClick("finalizeEvent(this)");

    $giftsButton = ActionButton::getDefaultButton('bi-gift', 'btn-pink', 'Regalos');
    $giftsButton->addAttribute('data-id', $id);
    $giftsButton->setClick("obtainGifts(this)");

    return $emailButton->getIconButton()."    ".$videoButton->getIconButton()."    ".$finalizeButton->getIconButton();
  }

  private function generateButtonsNotSoldOut($event): string
  {
    $paymentButton = ActionButton::getDefaultButton(' bi-cash', 'btn-info', 'Agregar Pago');
    $paymentButton->addAttribute('data-id', $event->id);
    $paymentButton->setClick("addPayment(this)");

    $editButton = ActionButton::getDefaultEditButton();
    $editButton->addAttribute('data-id', $event->id);
    $editButton->setClick("editEvent(this)");

    $deleteButton = ActionButton::getDefaultDeleteButton();
    $deleteButton->addAttribute('data-id', $event->id);
    $deleteButton->setClick("deleteEvent(this)");

    $cancelButton = ActionButton::getDefaultButton(' bi-calendar2-x', 'btn-danger', 'Cancelar Evento');
    $cancelButton->addAttribute('data-id', $event->id);
    $cancelButton->setClick('cancelEvent(this)');

    $finalButtons = $editButton->getIconButton()."    ";
    $finalButtons .= $event->status == 'CANCELADO' ? '' : $cancelButton->getIconButton();
    $finalButtons .= "    ". $paymentButton->getIconButton(). "    ". $this->generateShowPaymentButton($event);
    return $finalButtons;
  }

  private function generateButtonsFinalize($event): string
  {
    $giftsButton = ActionButton::getDefaultButton('bi-gift', 'btn-pink', 'Regalos');
    $giftsButton->addAttribute('data-id', $event->id);
    $giftsButton->setClick("obtainGifts(this)");

    return $giftsButton->getIconButton()."    ".$this->generateShowPaymentButton($event);
  }

  private function generateShowPaymentButton ($event): string
  {
    $showPaymentsButton = ActionButton::getDefaultButton(' bi-file-earmark-text', 'btn-light'
      , 'Mostrar Pagos');
    $showPaymentsButton->addAttribute('data-id', $event->id);
    $showPaymentsButton->addAttribute('data-customer', $event->customer);
    $showPaymentsButton->setClick("infoPayment(this)");
    return $showPaymentsButton->getIconButton();
}
}