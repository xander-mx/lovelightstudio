<?php

namespace App\Traits;

use App\Utils\ActionButton;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

trait ReservaDataTable
{
  use BaseDataTable;


  protected function getSearchDataResult(&$query)
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $addFilter = Auth::user()->idPerfil <= 2 ||Auth::user()->idPerfil == 8;
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where(function($query) use ($search) {
        $query->where('date', 'like', '%'. $search .'%')
          ->orWhere(DB::raw("CONCAT(client.name, ' ', COALESCE(client.lastname,''))"),'like','%' . $search . '%')
          ->orWhere('centers.name','like','%' . $search . '%')
          ->orWhere('num_habitacion', 'like', '%'. $search .'%');
      });

      $query->when($addFilter, function($query) use ($search) {
        $query->orWhere(DB::raw("CONCAT(receptionist.name, ' ', COALESCE(receptionist.lastname,''))"), 'like', '%'. $search .'%')
          ->orWhere(DB::raw("CONCAT(manager.name, ' ', COALESCE(manager.lastname,''))"), 'like', '%'. $search .'%');
      });

    }
    $this->searchConfig['recordsFiltered'] = $query->count();
    $reservations = $query->limit( $this->searchConfig['length'] )->offset( $this->searchConfig['start'] )
      ->orderBy('date', 'desc')->orderBy('schedules.hour', 'asc')->get();
    $data = [];
    foreach($reservations as $reservation)
      $data[] = $this->getDataRow($reservation);
    return $this->getSearchResult($data,false);
  }

  private function getDataRow($reservation) {
    $info =  new stdClass();
    if(Auth::user()->idPerfil <= 2 ||Auth::user()->idPerfil == 8) {
      $button = new ActionButton();
      $button ->setLabel(filled($reservation->manager) ? 'Cambiar' :'Asignar');
      $button->setHref("javascript:;");
      $button->setClick('openModal('.$reservation->idReservation.')');
      $button->addClassButton("btn-sm btn-warning");
      $button->removeClassButton('btn-info');
      $button->removeClassButton('btn-xs');
      $info->receptionist = $reservation->receptionist;
      $info->manager = (filled($reservation->manager) ? $reservation->manager. " </br>" : ""). $button->getRowButton();
    }

    $this->addStatus($reservation, $info);

    $info->date = date('d-m-Y', strtotime($reservation->date));
    $info->hour = $reservation->hour.":".$reservation->minute;
    $info->center = $reservation->center;
    $info->client = $reservation->client;
    $info->pax = $reservation->pax;

    if(Auth::user()->idPerfil == 3) {
      if(filled($reservation->id_reserva)) {
        $finalizeButton = new ActionButton();
        $finalizeButton->setClick('openModalFinalizar('. $reservation->idReservation .')');
        $finalizeButton->setLabel('Finalizar');
        $finalizeButton->setHref('javascript:;');
      } else {
        $clientButton = new ActionButton();
        $clientButton->removeClassButton('btn-info');
        $clientButton->removeClassButton('btn-xs');
        $clientButton->setHref(route('reserva.cliente', ['reserva' => $reservation->idReservation]));
        $clientButton->addClassButton('btn-sm btn-primary');
        $clientButton->setLabel('Ver formulario Cliente');
      }
    }

    $actionButton = new ActionButton();
    $actionButton->setLabel("Acciones");
    $actionButton->addClassButton('btn-sm btn-warning mr-2');
    $actionButton->removeClassButton('btn-info');
    $actionButton->removeClassButton('btn-xs');
    $actionButton->setClick("openAction(". $reservation->idReservation.",".$reservation->idCenter.")");
    $info->actions = $actionButton->getRowButton();
    $info->actions .= isset($finalizeButton) ? $finalizeButton->getRowButton() : "";
    $info->actions .= isset($clientButton) ? $clientButton->getRowButton() : "";

    return $info;
  }

  private function addStatus($reservation,&$info) {
    if(Auth::user()->idPerfil == 3 || Auth::user()->idPerfil == 4) {
      $labelStatus = "";
      switch ($reservation->state) {
        case 1: $labelStatus = "<span class='badge badge-success'>Activo</span>"; break;
        case 2: $labelStatus = "<span class='badge badge-warning'>Cancelado</span>"; break;
        case 3: $labelStatus = "<span class='badge badge-success'>Finalizado</span>"; break;
        case 4: $labelStatus = "<span class='badge badge-danger'>Re-Agendado</span>"; break;
      }
      $info->state = $labelStatus;
      $info->num_habitacion = (filled($reservation->num_habitacion) ? $reservation->num_habitacion : "S/H");
    }
  }
}