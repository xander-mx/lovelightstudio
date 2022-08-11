<?php

namespace App\Traits;

use App\Utils\ActionButton;

trait EventDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query) {

    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('customer', 'like', '%'. $search .'%')
        ->orWhere('service', 'like', '%'. $search .'%')
        ->orWhere('center', 'like', '%'. $search .'%')
        ->orWhere('photographer', 'like', '%'. $search .'%');
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
    $editButton = ActionButton::getDefaultEditButton();
    $editButton->addAttribute('data-id', $event->id);
    $editButton->setClick("editEvent(this)");

    $deleteButton = ActionButton::getDefaultDeleteButton();
    $deleteButton->addAttribute('data-id', $event->id);
    $deleteButton->setClick("deleteEvent(this)");

    return [
      'event_date' => date('d-m-Y', strtotime($event->event_date)),
      'customer' => $event->customer,
      'center' => $event->center,
      'service' => $event->service,
      'email' => $event->email,
      'phone' => $event->phone,
      'price' => '$ '.number_format($event->price,2),
      'actions' => $editButton->getIconButton()."    ". $deleteButton->getIconButton()
    ];
  }

}