<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\CommissionsDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionsController extends Controller
{
  use CommissionsDataTable;

  public static array $headers = array('Fecha', 'Trabajador', 'Cliente', 'Concepto', 'Comisión', 'Estado', 'Acciones');
  public static array $fields = array('date', 'employee', 'customer', 'concept', 'amount', 'status', 'actions');
  public static string $routeTable = 'web.commissions.table';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $commissions = DB::table('commissions')
      ->Join('users', 'users.idUser', '=', 'commissions.employee')
      ->leftJoin('reservations', 'commissions.reservation', '=', 'reservations.idReservation' )
      ->whereIn('status',['PENDIENTE', 'AUTORIZADO'])->when($this->searchConfig['employee'] != 0, function ($query) {
        $query->where('commissions.employee', '=',$this->searchConfig['employee']);
      })
      ->select('commissions.id', 'users.idUser', 'commissions.date', DB::raw('users.name as employee'),
        DB::raw('coalesce(reservations.customer, "") customer'), 'concept', 'amount', 'status');
    return $this->getSearchDataResult($commissions);
  }

  public function index()
  {
    $employees = User::whereIn('idPerfil',[2,3,4,8])->where('state',1)->orderBy('name')->get();
    $employees->prepend(["idUser" => 0, "name" => "Todos"]);
    return view('system.commissions.index', compact('employees'));
  }

}