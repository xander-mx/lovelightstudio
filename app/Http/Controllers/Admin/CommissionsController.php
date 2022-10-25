<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commissions;
use App\Models\Enums\CommissionStatus;
use App\Models\User;
use App\Traits\CommissionsDataTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

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
      ->leftJoin('reservations', 'commissions.reservation', '=', 'reservations.idReservation')
      ->whereIn('status',['PENDIENTE', 'AUTORIZADO'])->when($this->searchConfig['employee'] != 0,
        function ($query) {
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

  public function save(Request $request): JsonResponse
  {
    $rules = [
      'photographer' => 'required',
      'concept' => 'required',
      'amount' => 'required'
    ];

    $messages = [
      'photographer.required' => 'El empleado es requerido!!!',
      'concept.required' => 'El concepto es requerido!!!',
      'amount.required' => 'El monto es requerido!!!'
    ];

    DB::beginTransaction();
    try {
      $this->validate($request, $rules, $messages);
      $data = $request->all();
      $commission = filled($data['id']) ? Commissions::find($data['id'])->fill($data) : new Commissions($data);
      $commission->created_at = $commission->created_at ?? time();
      $commission->date = $commission->date ?? date('Y-m-d h:i:s');
      $commission->employee = $data['photographer'];
      $commission->status = CommissionStatus::memberByKey('PENDIENTE');
      $commission->save();
      DB::commit();
    } catch(Throwable $th) {
      DB::rollBack();
      $error = '';
      if(isset($th->validator) && sizeOf($th->validator->messages()) > 0) {
        foreach ($th->validator->messages()->all() as $message)
          $error .= $message. ' ';
      }  else {
        $error = $th->getMessage();
      }
      return response()->json(['message' => $error, 'code' => 401]);
    }
    return response()->json(['message' => 'Se creó comisión', 'code' => 200]);
  }

  public function changeStatus(string $status, int $id): JsonResponse
  {
    try{
      DB::beginTransaction();
      $commission = Commissions::find($id);
      $commission->status = CommissionStatus::memberByKey($status);
      $commission->authorized_by = Auth::user()->idUser;
      $commission->save();
      $message = $status == 'RECHAZADO' ? 'rechazó' : 'autorizó';
      DB::commit();
      return response()->json(['message' => 'Se '.$message.' la comisión', 'code' => 200]);
    } catch (Exception $ex) {
      DB::rollback();
      abort(406, $ex->getCode()." - ".$ex->getMessage());
    }
  }
}