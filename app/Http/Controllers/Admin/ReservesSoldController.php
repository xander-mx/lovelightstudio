<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ReservesSoldDataTable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservesSoldController extends Controller
{
  use ReservesSoldDataTable;
  public static array $headers = array('Fecha', 'Hotel', 'Cliente', 'Venta', 'Miscelaneo', 'Recibo', 'Reservacionista',
    'Fotógrafo', 'Tipo de Pago', 'Comisión Reserva', 'Comisión Fotógrafo', 'Comisión Supervisor Reserva', 'Emails',
    'Telefónos', 'Canvas', 'Autorización', 'Dirección');
  public static array $headersPhotographer = array('Fecha', 'Hotel', 'Cliente', 'Comisión Reserva', 'Comisión Fotógrafo');
  public static array $headersReader = array('Fecha', 'Hotel', 'Cliente', 'Venta');
  public static array $fields = array('date', 'center', 'customer', 'sale', 'miscellaneous', 'ticket', 'receptionist',
    'manager', 'payment_method', 'comission_recep', 'comission_manager', 'comission_super', 'emails', 'phones',
    'canva', 'authorized', 'direction');
  public static array $fieldsPhotographer = array('date', 'center', 'customer', 'comission_recep', 'comission_manager');
  public static array $fieldsReader = array('date', 'center', 'customer', 'sale');
  public static string $routeIndex = 'reserves.sold.table';

  public function loadTable(Request $request): array
  {
    $isPhotographer = Auth::user()->idPerfil == 3;
    $isCostaImage = Auth::user()->idPerfil == 9;
    $this->setUpSearchConfig($request);
    $reservations = $this->reservationsJoins()->where('reservations.state',3)
      ->when($isPhotographer, function($query) {
        $query->where('reservations.idManager','=', Auth::user()->idUser);
      })->when($isCostaImage, function($query) {
        $query->where('reservations.idCenter', '=', 8);
      })
      ->select('reservations.idReservation', 'reservations.date', DB::raw('centers.name as center'),
        DB::raw("reservations.customer as client"),
        DB::raw('COALESCE(reservations_info_finish.total_venta, 0) as total_venta'), 'reservations_info_finish.micelaneo',
        'reservations_info_finish.recibo',
        DB::raw("CONCAT(receptionist.name,' ', COALESCE(receptionist.lastname,'')) as receptionist"),
        DB::raw("CONCAT(manager.name,' ', COALESCE(manager.lastname,'')) as manager"), 'reservations.idManager',
        'reservations_info_finish.forma_cobro', 'reservations_info_finish.email', 'reservations_info_finish.email2',
        'reservations_info_finish.telefono1', 'reservations_info_finish.telefono2', 'reservations_info_finish.canva',
        'reservations_info_finish.autoriza_fotografia', 'reservations_info_finish.direccion', 'reservations.idCenter',
        'reservations.idClient', 'reservations.idReceptionist', 'reservations_info_finish.id_reserva');
    return $this->getSearchDataResult($reservations);
  }

  public function index() {
    return view('system.reservas.sold');
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
}