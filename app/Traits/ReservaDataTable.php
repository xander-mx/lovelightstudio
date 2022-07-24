<?php

namespace App\Traits;

use App\Utils\ActionButton;

trait ReservaDataTable
{
  use BaseDataTable;


  protected function getSearchDataResult(&$query)
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where(function($query) use ($search) {
        $query->where('date', 'like', '%'. $search .'%');
      });
    }
    $this->searchConfig['recordsFiltered'] = $query->count();
    $reservations = $query->limit( $this->searchConfig['length'] )->offset( $this->searchConfig['start'] )->orderBy('date', 'desc')->get();
    $data = [];
    foreach($reservations as $reservation)
      $data[] = $this->getDataRow($reservation);
    return $this->getSearchResult($data,false);
  }

  private function getDataRow($reservation) {
    $button = new ActionButton();
    $button ->setLabel(filled($reservation->manager) ? 'Cambiar' :'Asignar');
    $button->setHref("javascript:;");
    $button->setClick('openModal('.$reservation->idReservation.')');
    $button->addClassButton("btn-sm btn-warning");
    $button->removeClassButton('btn-info');
    $button->removeClassButton('btn-xs');
    $actionButton = new ActionButton();
    $actionButton->setLabel("Acciones");
    $actionButton->addClassButton('btn-sm btn-warning');
    $actionButton->removeClassButton('btn-info');
    $actionButton->removeClassButton('btn-xs');
    $actionButton->setClick("openAction(". $reservation->idReservation.",".$reservation->idCenter.")");
    $dataRow = [
      'date' => date('d-m-Y', strtotime($reservation->date)),
      'hour' => $reservation->schedule->hour.":".$reservation->schedule->minute,
      'center' => $reservation->center->name,
      'client' => $reservation->client->name. " ". (filled($reservation->client->lastname) ? filled($reservation->client->lastname) : ""),
      'receptionist' => $reservation->receptionist->name. " ". (filled($reservation->receptionist->lastname) ?
        $reservation->receptionist->lastname : ""),
      'manager' => (filled($reservation->manager) ? $reservation->manager->name. " ". (filled($reservation->receptionist
        ->lastname) ? filled($reservation->receptionist->lastname). " </br>" : "") : ""). $button->getRowButton(),
      'pax' => $reservation->pax,
      'actions' => $actionButton->getRowButton()
    ];
    return $dataRow;
  }
}