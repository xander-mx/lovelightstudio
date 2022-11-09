<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commissions;
use App\Models\Configurations;
use App\Models\Enums\CommissionStatus;
use App\Models\Enums\PayrollStatus;
use App\Models\Payrolls;
use App\Traits\PayrollDataTable;
use Carbon\Carbon;
use Exception;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PayrollController extends Controller
{
  use PayrollDataTable;

  const PAGADO = 'PAGADO';
  const AUTORIZADO = 'AUTORIZADO';
  public static array $headers = array('Fecha Creación', 'Concepto', 'Fecha Cierre', 'Estado', 'Total', 'Acciones');
  public static array $headersNext = array('Empleado', 'Total');
  public static array $fields = array('created_at', 'concept', 'closeDate', 'status', 'total', 'actions');
  public static array $fieldsNext = array('employee', 'total');
  public static string $routeTable = 'web.payrolls.table';
  public static string $routeNextTable = 'web.commissions.nextTable';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $payrolls = Payrolls::all()->sortByDesc('closedDate');
    return $this->getSearchDataResult($payrolls);
  }

  public function loadNextPayrollTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $commissions = DB::table('commissions')
      ->Join('users', 'users.idUser', '=', 'commissions.employee')
      ->where('status', '=', CommissionStatus::memberByKey('AUTORIZADO'))
      ->whereNull('id_payroll')
      ->selectRaw('commissions.employee id,CONCAT(users.name, " ", COALESCE(users.lastname, "")) employee, 
          SUM(commissions.amount) amount')->groupBy('commissions.employee');
    return $this->getSearchDataResultNext($commissions);
  }

  public function index()
  {
    $total = '$ '.number_format(Commissions::where('status', '=', 'AUTORIZADO')->whereNull('id_payroll')->sum('amount'));
    $reservations = '$ '.number_format(Commissions::where('status', '=', 'AUTORIZADO')->whereNotNull('reservation')
        ->whereNull('id_payroll')->sum('amount'));
    $others = '$ '.number_format(Commissions::where('status', '=', 'AUTORIZADO')->whereNull('reservation')
        ->whereNull('id_payroll')->sum('amount'));
    return view('system.payroll.index', compact('total', 'reservations', 'others'));
  }

  public function create(Request $request): JsonResponse
  {
    try{
      DB::beginTransaction();
      $commissions = Commissions::where('status', '=', 'AUTORIZADO')->sum('amount');
      if($commissions < 1) return abort(400, 'No hay comisiones!!!');
      if(Payrolls::where('status', '=', 'PENDIENTE')->first() !== null) return abort(400, 'Hay un corte pendiente!!!');
      $configuration = Configurations::where('name', '=', 'fecha_cierre')->where('state', '=', 1)->first();
      $lastPayroll = Payrolls::max('closeDate');
      $payroll = new Payrolls();
      $payroll->total = $commissions;
      $payroll->initDate = filled($lastPayroll) ? $lastPayroll->closeDate : $configuration->value;
      $payroll->closeDate = $configuration->value;
      $payroll->concept = $request['concept'] ?? 'Cierre de '. $payroll->initDate .' a '. $payroll->closeDate;
      $payroll->save();
      Commissions::where('status', '=', 'AUTORIZADO')->update(['status' => self::PAGADO, 'id_payroll' => $payroll->id]);
      DB::commit();
    } catch (Throwable $th) {
      DB::rollBack();
      $error = '';
      if(isset($th->validator) && sizeOf($th->validator->messages()) > 0) {
        foreach ($th->validator->messages()->all() as $message)
          $error .= $message. ' ';
      }  else {
        $error = $th->getMessage();
      }
      return abort(400, $error);
    }
    return response()->json(['message' => 'Cierre creado', 'code' => 200]);
  }

  public function changeStatus(int $id, string $status)
  {
    try {
      DB::beginTransaction();
      $payroll = Payrolls::find($id);
      throw_if(!isset($payroll), Throwable::class, 'No existe nomina!!');
      $payroll->status = PayrollStatus::memberByKey($status);
      if($status == self::PAGADO) {
        $payroll->closeDate = Carbon::now();
        Commissions::where('status', '=', CommissionStatus::memberByKey(self::AUTORIZADO))
          ->update(['status' => CommissionStatus::memberByKey(self::PAGADO) ]);
      }
      $payroll->save();
      DB::commit();
    }catch (Throwable $th) {
      DB::rollBack();
      return abort(400, $th->getMessage());
    }
    return response()->json(['message' => 'Nómina Autorizada', 'code' => 200]);
  }
}