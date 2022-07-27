<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParameterValues;
use App\Models\Reservations;
use App\Models\Schedules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ScheduleController extends Controller
{
    public function ajaxHorarios(Request $request)
    {
      $date = str_replace('/', '-',  $request->day);
      $dateFinal = date('Y-m-d H:i:s', strtotime($date . " 00:00:00"));
      $dateHoy = date('Y-m-d');
      $dateHoyFinal = date('Y-m-d H:i:s', strtotime($dateHoy . " 00:00:00"));
      $reservasDeldia = Reservations::where('idCenter',$request->idCenter)
        ->where(DB::raw('DATE(date)'), $dateFinal)->where('state',1)->get();
      $dataReservations = [];
      if($dateFinal == $dateHoyFinal){
        $hora = Carbon::now()->format('H');
      }else{
        $hora = "0";
      }

      $horamaxima = ParameterValues::where('idValueParameter',7)->first();
      foreach ($reservasDeldia as $value) {
        $data = new stdClass();
        //$data->schedule = "<a href='Javascript:;' onclick='openModalReserva($value->idSchedule)' class='btn btn-primary active'>". $value->schedule->hour.":".$value->schedule->minute."</a>";
        $data->schedule = "<a href='Javascript:;' class='btn btn-primary active'>". $value->schedule->hour.":".$value->schedule->minute."</a>";
        $data->client = $value->client->name;
        $data->pax = $value->pax;
        $dataReservations[] = $data;
      }
      $horarios = Schedules::where('state', 1)->where('hour','<',intval($horamaxima->extra))->get();
      return response()->json([
        'code' => 200,
        'data' => $horarios,
        'table' => $dataReservations,
        'message' => 'horarios obtenidos correctamente'
      ]);
    }
}