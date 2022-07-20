<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\comisiones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SueldosController extends Controller
{
    //
    public function index(Request $request)
    {
        $comisiones = comisiones::mes($request->mes)
        ->ano($request->ano)
        ->trabajador($request->trabajador)
        ->orderby('id_trabajador')
        ->where('state',1)
        ->get();
        $trabajadores = User::where('idPerfil','!=',1)
        ->where('idPerfil','!=',6)
        ->where('state',1)
        ->get();
        // dd($comisiones,count($comisiones));
        return view('system.sueldos.index',compact('comisiones','trabajadores'));
    }
    public function limpiar()
    {
        # code...
        $modulos = comisiones::truncate();

        // DB::beginTransaction();
        // try {

        //     $modulos = comisiones::truncate();

        //     DB::commit();

        // } catch (\Throwable $th) {
        //     //throw $th;
        // dd($th);

        // return redirect()->back()->with('message_error', 'error');   

        //     DB::rollBack();
        // }
        return redirect()->back()->with('message', 'Comisiones reseteadas correctamente');   

    }

    public function CrearComi(Request $request)
    {
        # code...
        # code...
        # code...
        $rules = [
            'idTrabajador' => 'required',
            'mon' => 'required', 
            'formaCobro' => 'required', 
        ];
      
        $messages = [
            'idTrabajador.required' => "El idTrabajador es requerido",
            'mon.required' => "El mon es requerido", 
            'formaCobro.required' => "El formaCobro es requerido", 
        ];
        $this->validate($request, $rules, $messages);
        // dd($request->all());

        DB::beginTransaction();
        try {

            $comision = new comisiones();
            $comision->id_trabajador = $request->idTrabajador;
            $comision->tipo_comision = $request->formaCobro;
            $comision->dia = date('d');
            $comision->mes = date('m');
            $comision->ano = date('Y');
            $comision->monto_comision = $request->mon;
            $comision->save();
            DB::commit();

        } catch (\Throwable $th) {
            //throw $th;
        dd($request->all(),$th);

        return redirect()->back()->with('message_error', 'error');   

            DB::rollBack();
        }
        return redirect()->back()->with('message', 'Sueldo ingresado'); 

    }


    public function changeState(Request $request)
    {
        # code...
        $rules = [
            'id' => 'required',
      
        ];
      
        $messages = [
            'id.required' => "El idReserva es requerido",
        ];
        $this->validate($request, $rules, $messages);
        DB::beginTransaction();
        try {
            $reserva = comisiones::where('id', $request->id)->first();
            $reserva->state = 3;
            $reserva->save();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
        // dd($request->all(),$th,$reserva);
        return redirect()->back()->with('message_error', 'error');   

            DB::rollBack();
        }

      
        return redirect()->back()->with('message', 'Comision eliminada asignado correctamente');   

    }

}
