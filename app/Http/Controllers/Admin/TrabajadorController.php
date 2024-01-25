<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParameterValues;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = User::whereNotIn('idPerfil', [1,6])->whereIn('state', [0,1])->get();
        $reservasNumeroHoy =[];
        foreach ($trabajadores as $key => $value) {
            # code...
            $reservasNumeroHoy[$value->idUser] = Count(Reservations::where('idManager',$value->idUser)
            ->where('state',1)
            ->get());
        }
        return view('system.trabajadores.index', compact('trabajadores','reservasNumeroHoy'));
    }

    public function createView()
    {
        $perfiles = ParameterValues::where('idParameter', 1) 
        ->whereNotIn('idValueParameter',[1,6])
        ->where('state', 1)->get();
        return view('system.trabajadores.create', compact('perfiles'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => ['required', 'email',
                Rule::exists('users', 'email')->where(function($query) {
                    return $query->where('state', '!=' ,1);
                })],
            'password' => 'required',
            'seguro_social' => 'required',
            'curp' => 'required',
            'confirmation_password' => 'required|same:password',
            'phone' => 'required',
            //'indicativo' => 'required|string',
            'perfil' => [
                'required',
                Rule::exists('parameter_values', 'idValueParameter')->where(function($query){
                    return $query->where('state', 1);
                })
            ]
        ],[
            'name.required' => "El nombre es requerido",
            'lastname.required' => "El apellido es requerido",
            'email.required' => "El correo es requerido",
            'email.exists' => "El correo ya fue registrado",
            'password.required' => "La contraseña es requerido",
            'confirmation_password.required' => "La contraseña de confirmacion es requerida",
            'confirmation_password.same' => "La contraseña de confirmacion debe ser igual a la contraseña",
            'phone.required' => "El teléfono es requerido",
            'perfil.required' => "El perfil es requerido",
            'seguro_social.required' => "El seguro_social es requerido",
            'curp.required' => "El curp es requerido",
            'perfil.exists' => "El perfil no existe",
        ]);

        if($validate->fails())
        {
            return back()->withErrors($validate->errors());
        }

        DB::beginTransaction();
        try {
            $trabajador = new User;
            $trabajador->name = $request->name;
            $trabajador->lastname = $request->lastname;
            $trabajador->email = $request->email;
            $trabajador->password = Hash::make($request->password);
            $trabajador->phone = $request->phone;
            $trabajador->indicative = "2";
            $trabajador->seguro_social =$request->seguro_social;
            $trabajador->curp =$request->curp;
            $trabajador->idPerfil = $request->perfil;
            $trabajador->state = 1;
            //$trabajador->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message_error', 'Hubo un error al guardar el trabajador, intentelo más tarde' . $th->getMessage());
            return back()->withInput();
        }

        Session::flash('message', 'El trabajador ha sido creado correctamente');
        return redirect()->route('trabajador.index');
    }
    public function edit(Request $request)
    {
        # code...
        if ($request->idUser != null) {
            # code...
            $usuario = User::where('idUser',$request->idUser)->first();
            $perfiles = ParameterValues::where('idParameter', 1) 
        ->whereNotIn('idValueParameter',[1,6])
        ->where('state', 1)->get();
        return view('system.trabajadores.edit', compact('perfiles','usuario'));

        }
    }
    public function put(Request $request)
    {
        # code...
        $validate = Validator::make($request->all(),[
            'id' => 'required|string',
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'phone' => 'required',
            'pwsd' => 'required',
        ],[
            'id.required' => "El id es requerido",
            'nombre.required' => "El nombre es requerido",
            'apellido.required' => "El apellido es requerido",
            'phone.required' => 'El teléfono es obligatorio',
            'pwsd.required' => "El pwsd es requerido",
        ]);

        if($validate->fails())
        {
            return back()->withErrors($validate->errors());
        }

        DB::beginTransaction();
        try {
            $trabajador = User::where('idUser',$request->id)->first();
            $trabajador->name = $request->nombre;
            $trabajador->lastname = $request->apellido;
            $trabajador->phone = $request->phone;
            $trabajador->password = Hash::make($request->password);
            $trabajador->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message_error', 'Hubo un error al guardar el trabajador, intentelo más tarde: '.$th->getMessage());
            return back()->withInput();
        }

        Session::flash('message', 'Estado cambiado correctamente');
        return redirect()->route('trabajador.index');
    }

    public function change(Request $request)
    {
        # code...
        $validate = Validator::make($request->all(),[
            'id' => 'required|string',
            'state' => 'required|string',
        ],[
            'id.required' => "El id es requerido",
            'state.required' => "El state es requerido",
        ]);

        if($validate->fails())
        {
            return back()->withErrors($validate->errors());
        }

        DB::beginTransaction();
        try {
            $trabajador = User::where('idUser',$request->id)->first();
            $trabajador->state = $request->state;
            $trabajador->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message_error', 'Hubo un error al guardar el trabajador, intentelo más tarde: '. $th->getMessage());
            return back()->withInput();
        }

        Session::flash('message', 'Estado cambiado correctamente');
        return redirect()->route('trabajador.index');
    }
}
