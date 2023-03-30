<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

trait ReservesSoldDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where(function($query) use ($search) {
        $query->where(DB::raw("DATE_FORMAT(date, '%d-%m-%Y')"), 'like', '%'. $search .'%')
          ->orWhere('reservations.customer','like','%' . $search . '%')
          ->orWhere('centers.name','like','%' . $search . '%')
          ->orWhere(DB::raw("CONCAT(receptionist.name, ' ', COALESCE(receptionist.lastname,''))"), 'like', '%'. $search .'%')
          ->orWhere(DB::raw("CONCAT(manager.name, ' ', COALESCE(manager.lastname,''))"), 'like', '%'. $search .'%')
          ->orWhere('reservations_info_finish.forma_cobro', 'like', '%'. $search .'%')
          ->orWhere('reservations_info_finish.recibo', 'like', '%'. $search .'%');
      });
    }
    $query->whereBetween('reservations.date', [$this->searchConfig['init_date'], $this->searchConfig['end_date']]);
    $this->searchConfig['recordsFiltered'] = $query->count();
    $reservations = $query->limit( $this->searchConfig['length'] )->offset( $this->searchConfig['start'] )
      ->orderBy('date', 'desc')->get();
    $data = [];
    foreach($reservations as $reservation)
      $data[] = $this->getDataRow($reservation);
    return $this->getSearchResult($data,false);
  }

  private function getDataRow($reservation): stdClass
  {
    $info =  new stdClass();

    $info->id = $reservation->idReservation;
    $info->date = date('d-m-Y', strtotime($reservation->date));
    $info->center = $reservation->center;
    $info->customer = $reservation->client;
    if(Auth::user()->idPerfil <= 2 || Auth::user()->idPerfil == 8 || Auth::user()->idPerfil == 9) {
      if(Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 9) $info->sale = '$'.number_format($reservation->total_venta);
      $info->miscellaneous = $reservation->micelaneo;
      $info->ticket = $reservation->recibo;
      $info->receptionist = $reservation->receptionist;
      $info->manager = $reservation->manager;
      $info->payment_method = $reservation->forma_cobro;
      $info->comission_recep = ($reservation->total_venta <= 500 ? '$150' : '$300');
      $info->comission_manager = '$'.number_format(($reservation->total_venta * .21));
      $info->comission_super = '$50';
      $info->emails = $reservation->email.', '.$reservation->email2;
      $info->phones = $reservation->telefono1.', '.$reservation->telefono2;
      $info->canva = filled($reservation->canva) ? $reservation->canva : '-';
      $info->authorized = $reservation->autoriza_fotografia;
      $info->direction = $reservation->direccion;
    } else {
      if (Auth::user()->idUser == $reservation->idReceptionist) {
        $info->comission_recep = ($reservation->total_venta <= 500 ? '$150' : '$300');
      } else {
        $info->comission_recep = "-";
      }

      if (Auth::user()->idUser == $reservation->idManager) {
        $info->comission_manager = '$'.number_format(($reservation->total_venta * .21));
      } else {
        $info->comission_manager = "-";
      }
    }
    return $info;
  }

}