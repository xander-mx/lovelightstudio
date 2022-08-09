<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centers;
use App\Models\Events;
use App\Models\Services;
use App\Traits\EventDataTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
  use EventDataTable;
  public static array $headers = array('Cliente', 'Hotel', 'Paquete', 'Fotógrafo', 'Teléfono', 'Costo', 'Fecha', 'Acciones');
  public static array $fields = array('customer', 'center', 'service', 'photographer', 'phone', 'price', 'event_date', 'actions');
  public static string $routeIndexTable = 'weddings.events.table';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $events = DB::table('events');
    return $this->getSearchDataResult($events);
  }

  public function index() {
    $centers = Centers::all();
    $services = Services::all();
    return view('system.weddings.events.index', compact('centers', 'services'));
  }

  public function save(Request $request): JsonResponse
  {
    $rules = [
      'customer' => 'required',
      'eventDate' => 'required',
      'center' => 'required',
      'service' => 'required'
    ];

    $messages = [
      'customer.required' => 'El cliente requerido!!!',
      'eventDate.required' => 'Fecha del evento es requerida!!!',
      'center.required' => 'El hotel es requerido!!!',
      'service.required' => 'El paquete es requerido!!!'
    ];

    $this->validate($request, $rules, $messages);
    $data = $request->all();

    DB::beginTransaction();
    try {
      $event = filled($data['id']) ? Events::find($data['id'])->fill($data) : new Events($data);
      $event->created_at = filled($event->created_at) ? $event->created_at : time();
      $center = Centers::find($data['center']);
      $service = Services::find($data['service']);
      if($service->id != 16) {
        $event->price = $service->price;
      }
      $event->event_date =date('Y-m-d H:i:s', strtotime($data['eventDate'] . " 00:00:00"));
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

  public function delete($id) {
    try {
      Events::destroy($id);
    } catch  (\Throwable $th) {
      return response()->json(['message' => 'Error '. $th->getMessage(), 'code' => 401]);
    }
    return response()->json(['message' => 'Se elimino con exito!!!', 'code' => 200]);
  }
}