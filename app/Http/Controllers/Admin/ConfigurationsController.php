<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configurations;
use App\Traits\ConfigurationsDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ConfigurationsController extends Controller
{

  use ConfigurationsDataTable;

  public static array $headers = array('Nombre', 'Valor', 'Acciones');
  public static array $fields = array('name', 'value', 'actions');
  public static string $routeTable = 'web.configurations.table';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $configurations = Configurations::all();
    return $this->getSearchDataResult($configurations);
  }

  public function index()
  {
    return view('system.configurations.index');
  }

  public function getInformation(Request $request, int $id): JsonResponse
  {
    $configuration = Configurations::find($id);
    if($configuration == null) {
      return abort('No existe la configuración!!!',400);
    }
    return response()->json(['data' => $configuration, 'code' => 200]);
  }

  public function update(Request $request): JsonResponse
  {
    $rules = [
      'id' => 'required',
      'value' => 'required'
    ];
    $messages = [
      'id.required' => 'Configuración necesaria!!!',
      'value.required' => 'El valor es obligatorio!!!'
    ];
    try{
      DB::beginTransaction();
      $this->validate($request, $rules, $messages);
      $data = $request->all();
      $configuration = Configurations::find($data['id'])->fill($data);
      $configuration->updated_by = Auth::user()->idUser;
      $configuration->save();
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
    return response()->json(['message' => 'Se actualizó configuración', 'code' => 200]);
  }

}