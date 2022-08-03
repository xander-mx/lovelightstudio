<?php

namespace App\Traits;

use App\Utils\ActionButton;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

trait ReservaDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(): array
  {
    $search = strtoupper( $this->searchConfig['search'] );
    $query = $this->reservationsSelect('>=');
    $query2 = $this->reservationsSelect('<');
    if (!empty($search)) {
     $this->reservationsWheres($query, $search);
     $this->reservationsWheres($query2, $search);
    }
    $query->orderBy('reservations.date')->orderBy('schedules.hour')->orderBy('schedules.minute');
    $query2->orderBy('reservations.date','desc')->orderBy('schedules.hour')
      ->orderBy('schedules.minute');
    $queryFinal = $query->union($query2);

    $this->searchConfig['recordsFiltered'] = $queryFinal->count();

    $reservations = $queryFinal->limit( $this->searchConfig['length'] )->offset( $this->searchConfig['start'] )->get();
    $data = [];
    foreach($reservations as $reservation)
      $data[] = $this->getDataRow($reservation);
    return $this->getSearchResult($data,false);
  }

  private function getDataRow($reservation): stdClass
  {
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
        $finalizeButton->removeClassButton('btn-xs');
        $finalizeButton->addClassButton('btn-sm ');
        $finalizeButton->setClick('openModalFinalizar('. $reservation->idReservation .')');
        $finalizeButton->setLabel('Finalizar');
        $finalizeButton->setHref('javascript:;');
      } else {
        $clientButton = new ActionButton();
        $clientButton->removeClassButton('btn-info');
        $clientButton->removeClassButton('btn-xs');
        $clientButton->setClick('openModalClient('. $reservation->idReservation .')');
        //$clientButton->setHref(route('reserva.cliente', ['reserva' => $reservation->idReservation]));
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

  private function reservationsSelect($conditional) {
    $isPhotographer = Auth::user()->idPerfil == 3;
    $date = date('Y-m-d');
    $reservations = $this->reservationsJoins()
      ->where('reservations.state',1)->where(DB::raw('DATE(date)'),$conditional,$date)->when($isPhotographer, function($query) {
        $query->where('reservations.idManager','=', Auth::user()->idUser);
      })
      ->select('reservations.idReservation', 'reservations.date', 'reservations.pax', 'reservations.idCenter',
        'reservations.num_habitacion', 'reservations.state', DB::raw("reservations.customer as client"),
        DB::raw("CONCAT(receptionist.name,' ', COALESCE(receptionist.lastname,'')) as receptionist"), 'reservations.idManager',
        DB::raw("CONCAT(manager.name,' ', COALESCE(manager.lastname,'')) as manager"),'reservations.idClient', 'reservations.idReceptionist',
        DB::raw('centers.name as center'), DB::raw('schedules.hour'), DB::raw('schedules.minute')
        , 'reservations_info_finish.id_reserva');

    return $reservations;
  }

  private function reservationsJoins(): Builder
  {
    return DB::table('reservations')
      ->leftJoin(DB::raw('users as receptionist'), 'reservations.idReceptionist', '=', 'receptionist.idUser')
      ->leftJoin(DB::raw('users as manager'), 'reservations.idManager', '=', 'manager.idUser')
      ->join('centers', 'reservations.idCenter', '=', 'centers.idCenter')
      ->join('schedules', 'reservations.idSchedule', '=', 'schedules.idSchedule')
      ->leftJoin('reservations_info_finish', 'reservations.idReservation','=','reservations_info_finish.id_reserva');
  }

  private function reservationsWheres(&$query, $search) {
    $addFilter = Auth::user()->idPerfil <= 2 ||Auth::user()->idPerfil == 8;
    $query->where(function($query) use ($search) {
      $query->where(DB::raw("DATE_FORMAT(date, '%d-%m-%Y')"), 'like', '%'. $search .'%')
        ->orWhere('customer','like','%' . $search . '%')
        ->orWhere('centers.name','like','%' . $search . '%')
        ->orWhere('num_habitacion', 'like', '%'. $search .'%');
    });

    $query->when($addFilter, function($query) use ($search) {
      $query->orWhere(DB::raw("CONCAT(receptionist.name, ' ', COALESCE(receptionist.lastname,''))"), 'like', '%'. $search .'%')
        ->orWhere(DB::raw("CONCAT(manager.name, ' ', COALESCE(manager.lastname,''))"), 'like', '%'. $search .'%');
    });
  }
}