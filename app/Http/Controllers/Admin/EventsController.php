<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailingTo;
use App\Models\Centers;
use App\Models\Enums\EventStatus;
use App\Models\Enums\PaymentType;
use App\Models\Events;
use App\Models\Services;
use App\Traits\EventDataTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EventsController extends Controller
{
  use EventDataTable;

  const LIQUIDADO = 'LIQUIDADO';
  const FINALIZADO = 'FINALIZADO';
  const CANCELADO = 'CANCELADO';
  public static array $headers = array('Fecha','Cliente', 'Hotel', 'Paquete', 'Email', 'Coordinador/a', 'Teléfono', 'Costo', 'Saldo', 'Acciones');
  public static array $fields = array('event_date', 'customer', 'center', 'service', 'email', 'coordinator', 'phone', 'price', 'balance', 'actions');
  public static array $headersPayments = array('Fecha de Pago', 'Monto', 'Voucher', 'Forma de Pago');
  public static array $fieldsPayments = array('payment_date', 'amount', 'voucher', 'payment_type');
  public static string $routeIndexTable = 'weddings.events.table';
  public static string $routeIndexTablePayments = 'weddings.events.payments.table';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $events = DB::table('events')->where('status', $this->searchConfig['status'] );
    return $this->getSearchDataResult($events);
  }

  public function loadPaymentsTable(Request $request, int $id): array
  {
    $arr = [];
    $this->setUpSearchConfig($request);
    $event = Events::find($id);
    if(!isset($event) || sizeof($event->payments) == 0) return $this->getSearchResult($arr);
    return $this->getSearchDataResultPayments($event->payments);
  }

  public function index() {
    $centers = Centers::all();
    $services = Services::all();
    $external = env('EXTERNAL_HOTEL');
    $service_personalize = env('SERVICE_PERSONALIZE');
    $status = EventStatus::members();
    $statusBtns = [['label' => 'Vendidos', 'class' => 'btn-warning', 'fn' => "changeStatusFilter('LIQUIDADO')"],
      ['label' => 'Cancelados', 'class' => 'btn-danger', 'fn' => "changeStatusFilter('CANCELADO')"]
      , ['label' => 'En Proceso', 'class' => 'btn-success', 'fn' => "changeStatusFilter('EN_PROCESO')"]
      , ['label' => 'Finalizados', 'class' => 'btn-info', 'fn' => "changeStatusFilter('FINALIZADO')"]];
    $paymentTypes = PaymentType::members();
    return view('system.weddings.events.index', compact('centers', 'services', 'external',
      'service_personalize', 'status', 'paymentTypes', 'statusBtns'));
  }

  public function save(Request $request): JsonResponse
  {
    $rules = [
      'customer' => 'required',
      'eventDate' => 'required',
      'center' => 'required',
      'service' => 'required',
      'coordinator' => 'required'
    ];

    $messages = [
      'customer.required' => 'El cliente requerido!!!',
      'eventDate.required' => 'Fecha del evento es requerida!!!',
      'center.required' => 'El hotel es requerido!!!',
      'service.required' => 'El paquete es requerido!!!',
      'coordinator.required' => 'El Coordinador/a es requerido!!!'
    ];
    $service_personalize = env('SERVICE_PERSONALIZE');
    $this->validate($request, $rules, $messages);
    $data = $request->all();

    DB::beginTransaction();
    try {
      $event = filled($data['id']) ? Events::find($data['id'])->fill($data) : new Events($data);
      $event->created_at = filled($event->created_at) ? $event->created_at : time();
      $center = Centers::find($data['center']);
      $service = Services::find($data['service']);
      if($service->id != $service_personalize) {
        $event->price = $service->price;
      }
      if(!filled($data['id'])) $event->balance = $event->price;
      if(filled($data['discount']) && $data['discount'] > 0) $event->balance -= $event->discount;
      $event->event_date = date('Y-m-d H:i:s', strtotime($data['eventDate'] . " 00:00:00"));
      $event->phone = str_replace(' ','',$data['phone']);
      $event->id_center = $center->idCenter;
      $event->center = $center->name;
      $event->service = $service->name;
      $event->id_service = $service->id;

      $event->save();
      DB::commit();
    } catch (Exception $ex) {
      DB::rollback();
      return response()->json([
        'code' => 401,
        'data' => null,
        'message' => $ex->getCode()." - ".$ex->getMessage(),
        'th' => $ex
      ]);
    }
    return response()->json(['message' => 'Se creo el evento', 'code' => 200]);
  }

  public function getInformation($id): JsonResponse
  {
    $event = Events::find($id);
    if($event == null) {
      return response()->json(['message' => 'No existe el evento!!!', 'code' => 401]);
    }
    return response()->json(['data' => $event, 'code' => 200]);
  }

  public function delete(int $id): JsonResponse
  {
    try {
      Events::destroy($id);
    } catch  (Throwable $th) {
      return response()->json(['message' => 'Error '. $th->getMessage(), 'code' => 401]);
    }
    return response()->json(['message' => 'Se elimino con exito!!!', 'code' => 200]);
  }

  public function saveVideo(Request $request): JsonResponse
  {
    $rules = [
      'id' => 'required',
      'video' => 'required'
    ];
    $messages = [
      'id' => 'El evento es requerido!!!',
      'video' => 'Link de video requerido!!!'
    ];

    try {
      DB::beginTransaction();
      $this->validate($request, $rules, $messages);
      $data = $request->all();
      $event = Events::find($data['id']);
      $event->video = $data['video'];
      $event->code = filled($event->code) ? $event->code : rand(1000,10000);
      $event->save();
      DB::commit();
      if(!env('TESTING')) {
        $emails[] = $event->email;
        if($data['email'])
          $emails[] = $data['email'];
        Mail::to($emails)->send(new EmailingTo($event, 'mail.videos', 'Links Boda',
          env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')));
        Mail::to(env('MAIL_INFO'))->send(new EmailingTo($event, 'mail.links_to_info', 'Links Boda',
          env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')));
      }
    } catch (Throwable $th) {
      DB::rollBack();
      $error = '';
      if(sizeOf($th->validator->messages()) > 0) {
        foreach ($th->validator->messages()->all() as $message)
          $error .= $message. ' ';
      }  else {
        $error = $th->getMessage();
      }
      return response()->json(['message' => $error, 'code' => 401]);
    }
    return response()->json(['message' => 'Video enviado!!!', 'code' => 200]);
  }

  public function saveLinks(Request $request): JsonResponse
  {
    $rules = [
      'id' => 'required',
      'photos' => 'required'
    ];
    $messages = [
      'photos.required' => 'El link de fotos es obligatorio!!!',
      'id.required' => 'El evento es requerido!!!',
    ];
    try {
      DB::beginTransaction();
      $this->validate($request, $rules, $messages);
      $data = $request->all();
      $event = Events::find($data['id']);
      if($event == null) {
        return response()->json(['message' => 'No existe el evento!!!', 'code' => 401]);
      }
      $event->photos = $data['photos'];
      $event->canvas = filled($data['canvas']) ? $data['canvas'] : 1;
      $event->photobooks = filled($data['photobooks']) ? $data['photobooks'] : 1;
      $event->code = filled($event->code) ? $event->code : rand(1000,10000);
      $event->save();
      if(!env('TESTING')) {
        $emails[] = $event->email;
        if($data['email'])
          $emails[] = $data['email'];
        Mail::to($emails)->send(new EmailingTo($event, 'mail.photos', 'Fotos Boda',
          env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')));
        Mail::to(env('MAIL_INFO'))->send(new EmailingTo($event, 'mail.links_to_info', 'Links Boda',
          env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')));
      }
      DB::commit();
    } catch (Throwable $th) {
      DB::rollBack();
      $error = '';
      if(sizeOf($th->validator->messages()) > 0) {
        foreach ($th->validator->messages()->all() as $message)
          $error .= $message. ' ';
      }  else {
        $error = $th->getMessage();
      }
      return response()->json(['message' => $error, 'code' => 401]);
    }
    return response()->json(['message' => 'Links enviados!!!', 'code' => 200]);
  }

  public function finalize(int $id): JsonResponse
  {
    try {
      DB::beginTransaction();
      $event = Events::find($id);
      throw_if($event->status != self::LIQUIDADO || $event->balance > 0, "No es posible cerrar el evento!!!");
      $event->status = self::FINALIZADO;
      $event->save();
      DB::commit();
    } catch  (Throwable $th) {
      DB::rollBack();
      return response()->json(['message' => 'Error '. $th->getMessage(), 'code' => 401]);
    }
    return response()->json(['message' => 'Evento cerrado con exito!!!', 'code' => 200]);
  }

  public function canvas(Request $request, int $id, String $code)
  {
    $event = Events::find($id);
    throw_if(!$event || $event->code != $code, 'no existe');
    return view('system.weddings.events.canvas', compact('event'));
  }

  public function cancel(int $id) {
    try {
      DB::beginTransaction();
      $event = Events::find($id);
      throw_if(!isset($event) , "No existe el evento!!!");
      $event->status = self::CANCELADO;
      $event->save();
      DB::commit();
    }catch (Throwable $th) {
      DB::rollBack();
      return response()->json(['message' => 'Error '. $th->getMessage(), 'code' => 401]);
    }
  }

  public function autocomplete(string $search): array
  {
    $result = [];
    $events = Events::where('balance','>',0)->where('customer','like', '%'. $search . '%')->skip(0)->take(10)->get();
    foreach ($events as $event) {
      $result[] = ['label' => $event->customer, 'value' => $event->customer, 'event' => $event->id];
    }
    return $result;
  }
}