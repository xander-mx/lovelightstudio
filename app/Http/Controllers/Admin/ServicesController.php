<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Traits\ServiceDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
  use ServiceDataTable;

  public static $headers = array('Paquete', 'Descripción', 'Precio MX', 'Precio Dlls', 'Acciones');
  public static $fields = array('name', 'description', 'price', 'dlls', 'actions');
  public static $routeIndexTable = 'weddings.services.table';

  public function loadTable(Request $request) {
    $this->setUpSearchConfig($request);
    $services = Services::where('status', 1);
    return $this->getSearchDataResult($services);
  }

  public function index() {
    return view('system.weddings.services.index');
  }

  public function save(Request $request): JsonResponse
  {
    $rules = [
      'name' => 'required',
      'price' => 'required'
    ];

    $messages = [
      'name.required' => 'Nombre del paquete requerido!!!',
      'price.required' => 'Precio del paquete requerido!!!'
    ];

    $this->validate($request, $rules, $messages);
    $data = $request->all();

    DB::beginTransaction();
    try {
      $service = filled($data['id']) ? Services::find($data['id'])->fill($data) : new Services($data);
      $service->created_at = filled($service->created_at) ? $service->created_at : time();
      $service->status = 1;
      $service->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json([
        'code' => 401,
        'data' => null,
        'message' => $th->getCode()." - ".$th->getMessage(),
        'th' => $th
      ]);
    }

    return response()->json(['message' => 'Se creo el paquete', 'code' => 200]);
  }

  public function getInformation($id) {
    $service = Services::find($id);
    if($service == null) {
      return response()->json(['message' => 'No existe el paquete!!!', 'code' => 401]);
    }
    return response()->json(['data' => $service, 'code' => 200]);
  }

  public function delete($id) {
    try {
      Services::destroy($id);
    } catch  (\Throwable $th) {
      return response()->json(['message' => 'Error '. $th->getMessage(), 'code' => 401]);
    }
    return response()->json(['message' => 'Se elimino con exito!!!', 'code' => 200]);
  }

}