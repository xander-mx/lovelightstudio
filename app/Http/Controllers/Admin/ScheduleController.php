<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParameterValues;
use App\Models\Reservations;
use App\Models\Schedules;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function ajaxHorarios(Request $request)
    {
        // dd($request->all());
        $date = str_replace('/', '-',  $request->day);
        $dateFinal = date('Y-m-d H:i:s', strtotime($date . " 00:00:00"));
        $dateHoy = date('Y-m-d');
        $dateHoyFinal = date('Y-m-d H:i:s', strtotime($dateHoy . " 00:00:00"));
        // dd($dateFinal,$dateHoy,$dateHoyFinal);
        $idCenter = $request->id;
        $reservasDeldia = Reservations::where('idCenter',$request->idCenter)
        ->get();
        $horarioEvadir = [];
        if($dateFinal == $dateHoyFinal){
            $hora = Carbon::now()->format('H');
        }else{
            $hora = "0";
        }
        $horamaxima = ParameterValues::where('idValueParameter',7)->first();

        // dd($hora);
        foreach ($reservasDeldia as $key => $value) {
            # code...
            $horarioEvadir[] = $value->idSchedule;
        }
        // dd($horarioEvadir,$hora,$horamaxima,intval($horamaxima->extra));

        $horarios = Schedules::where('state', 1)
        // ->where('hour','>',$hora)
        ->where('hour','<',intval($horamaxima->extra))
        // ->whereNotIn('idSchedule',$horarioEvadir)
        ->get();

        return response()->json([
            'code' => 200,
            'data' => $horarios,
            'message' => 'horarios obtenidos correctamente'
        ]);
    }
}