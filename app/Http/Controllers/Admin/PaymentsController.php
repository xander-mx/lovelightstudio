<?php

namespace App\Http\Controllers\Admin;

use App\Models\Enums\PaymentType;
use App\Models\Events;
use App\Models\Payments;
use App\Traits\PaymentsDataTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
  use PaymentsDataTable;
  public static array $headers = array('Fecha de Pago','Cliente', 'Monto', 'Voucher', 'Forma de Pago', 'Acciones');
  public static array $fields = array('payment_date', 'customer', 'amount', 'voucher', 'payment_type', 'actions');
  public static string $routeIndexTable = 'weddings.payments.table';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $payments = DB::table('payments')->join('events', 'payments.id_event', 'events.id')
      ->select(['payments.id', 'amount', 'voucher', 'payment_type', 'payment_date', 'events.customer']);
    return $this->getSearchDataResult($payments);
  }

  public function index() {
    $paymentTypes = PaymentType::members();
    return view('system.weddings.payments.index', compact('paymentTypes'));
  }

  public function save(Request $request): JsonResponse
  {
    $rules = [
      'id_event' => 'required',
      'payment_type' => 'required',
      'amount' => 'required',
      'voucher' => 'required'
    ];

    $messages = [
      'id_event.required' => 'El evento es requerido!!!',
      'amount.required' => 'El monto es requerido!!!',
      'voucher.required' => 'El número de voucher es requerido!!!'
    ];

    $this->validate($request, $rules, $messages);
    $data = $request->all();

    DB::beginTransaction();
    try {
      $payment = filled($data['id']) ? Payments::find($data['id'])->fill($data) : new Payments($data);
      $payment->created_at = filled($payment->created_at) ? $payment->created_at : time();
      $payment->payment_date = date('Y-m-d H:i:s', strtotime($data['paymentDate'] . " 00:00:00"));
      $event = Events::find($data['id_event']);
      $event->balance -=  $data['amount'];
      $payment->save();
      $event->save();
      DB::commit();
    }catch (Exception $ex) {
      DB::rollback();
      return response()->json([
        'code' => 401,
        'data' => null,
        'message' => $ex->getCode()." - ".$ex->getMessage(),
        'th' => $ex
      ]);
    }
    return response()->json(['message' => 'Se registro el pago', 'code' => 200]);
  }

  public function getInformation(int $id): JsonResponse
  {
    $payment = Payments::with('event')->find($id);
    if($payment == null) {
      return response()->json(['message' => 'No existe el pago!!!', 'code' => 401]);
    }
    return response()->json(['data' => $payment, 'code' => 200]);
  }

  public function delete(int $id): JsonResponse
  {
    try {
      $payment = Payments::find($id)->get();
      $event = Events::find($payment[0]->id_event);
      $event->balance += $payment[0]->amount;
      Payments::destroy($id);
      $event->save();
    } catch  (\Throwable $th) {
      return response()->json(['message' => 'Error '. $th->getMessage(), 'code' => 401]);
    }
    return response()->json(['message' => 'Se elimino con exito!!!', 'code' => 200]);
  }
}