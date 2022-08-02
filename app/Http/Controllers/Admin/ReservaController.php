<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\avisarCliente;
use App\Mail\avisarFotografo;
use App\Mail\AvisarSupervisor;
use App\Mail\avisarSupervisor2;
use App\Mail\cancelacion;
use App\Mail\CorreoFinalizar;
use App\Models\Centers;
use App\Models\comisiones;
use App\Models\ParameterValues;
use App\Models\ReservaInfo;
use App\Models\Reservations;
use App\Models\Schedules;
use App\Models\User;
use App\Notifications\notifyFotografo;
use App\Notifications\sendSupervisor;
use App\Traits\ReservaDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class ReservaController extends Controller
{

  use ReservaDataTable;
  public static $headers = array('Horario', 'Cliente', 'Pax');
  public static $indexHeaders = array('Fecha', 'Hora', 'Hotel', 'Nombre', 'Recepcionista', 'Encargado', 'Pax', 'Acciones');
  public static $indexHeadersPhotographer = array('Fecha', 'Hora', 'Hotel', 'Nombre', 'Habitación', 'Pax', 'Estado', 'Acciones');
  public static $indexHeadersClient = array('Nombre', 'Hotel', 'Habitación', 'Fecha', 'Hora', 'Pax', 'Estado', 'Acciones');
  public static $fields = array('schedule', 'client', 'pax');
  public static $indexFields = array('date', 'hour', 'center', 'client', 'receptionist', 'manager', 'pax', 'actions');
  public static $indexFieldsPhotographer = array('date', 'hour', 'center', 'client', 'num_habitacion', 'pax', 'state', 'actions');
  public static $indexFieldsClient = array('client', 'center', 'num_habitacion', 'date', 'hour', 'pax', 'state', 'actions');
  public static $routeIndexTable = 'reserva.table';

  public function loadTable(Request $request) {
    $isPhotographer = Auth::user()->idPerfil == 3;
    $this->setUpSearchConfig($request);
    $reservations = DB::table('reservations')
      ->leftJoin(DB::raw('users as receptionist'), 'reservations.idReceptionist', '=', 'receptionist.idUser')
      ->leftJoin(DB::raw('users as manager'), 'reservations.idManager', '=', 'manager.idUser')
      ->join('centers', 'reservations.idCenter', '=', 'centers.idCenter')
      ->join('schedules', 'reservations.idSchedule', '=', 'schedules.idSchedule')
      ->leftJoin('reservations_info_finish', 'reservations.idReservation','=','reservations_info_finish.id_reserva')
      ->where('reservations.state',1)->when($isPhotographer, function($query) {
        $query->where('reservations.idManager','=', Auth::user()->idUser);
      })
      ->select('reservations.idReservation', 'reservations.date', 'reservations.pax', 'reservations.idCenter',
      'reservations.num_habitacion', 'reservations.state', DB::raw("reservations.customer as client"),
      DB::raw("CONCAT(receptionist.name,' ', COALESCE(receptionist.lastname,'')) as receptionist"), 'reservations.idManager',
      DB::raw("CONCAT(manager.name,' ', COALESCE(manager.lastname,'')) as manager"),'reservations.idClient', 'reservations.idReceptionist',
      DB::raw('centers.name as center'), DB::raw('schedules.hour'), DB::raw('schedules.minute')
      , 'reservations_info_finish.id_reserva');
    return $this->getSearchDataResult($reservations);
  }

    public function index()
    {
        if (Auth::user()->idPerfil <= 2 ||Auth::user()->idPerfil == 8) {
            $reservations = Reservations::where('state', 1)
            ->orderby('date')
            ->orderby('idSchedule')
            ->get();
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $trabajadores = User::where('idPerfil',3)->where('state',1)->get();
            $reservasNumeroHoy = [];
            foreach ($trabajadores as $key => $value) {
                # code...
                $reservasNumeroHoy[$value->idUser] = Count(Reservations::where('idManager',$value->idUser)
                ->where('state',1)
                ->get());
            }
        } else {
            if (Auth::user()->idPerfil == 3) {
                # code...
                $reservations = Reservations::where('idManager',Auth::user()->idUser)->where('state',1)
            ->orderby('date')
            ->get();
            }else{
                $reservations = Reservations::where('idReceptionist', Auth::user()->idUser)->orWhere('idManager', Auth::user()->idUser)->where('state', 1)
            ->orderby('date')
                
                ->get();
            }
            $trabajadores = User::where('idPerfil',3)->where('state',1)->get();
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $reservasNumeroHoy = [];
            foreach ($trabajadores as $key => $value) {
                # code...
                $reservasNumeroHoy[$value->idUser] = Count(Reservations::where('idManager',$value->idUser)
                ->where('state',1)
                ->get());
            }

        }
        return view('system.reservas.index', compact('reservations','horamaxima','trabajadores','reservasNumeroHoy'));
    }

    public function reservaFinalizada(Request $request)
    {
            $reservations = Reservations::where('state', 5)->get();
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $trabajadores = User::where('idPerfil',3)->get();
            $reservasNumeroHoy = [];
            foreach ($trabajadores as $key => $value) {
                # code...
                $reservasNumeroHoy[$value->name] = Count(Reservations::where('idManager',$value->idUser)
                ->where('state',1)
                ->get());
            }
        
        return view('system.reservas.finalizar', compact('reservations','horamaxima','trabajadores','reservasNumeroHoy'));
    }

    public function finalizarSupervisor(Request $request)
    {
        # code...
        # code...
        $rules = [
            'idReserva' => 'required',
            'link' => 'required', 
            'pwsd' => 'required', 
        ];
      
        $messages = [
            'idReserva.required' => "El idReserva es requerido",
            'link.required' => "El formaCobro es requerido", 
            'pwsd.required' => "El montoCobrado es requerido", 
        ];
        $this->validate($request, $rules, $messages);
        // dd($request->all());

        DB::beginTransaction();
        try {

            $reserva = Reservations::where('idReservation', $request->idReserva)->first();
            // $reserva->state =3;//osea falta que el admin o el otro cabron finalice
            $reserva->update();
            $reservaInfo = ReservaInfo::where('id',$reserva->receptioninfo->id)->first();
            $reservaInfo->linkPagina = $request->link;
            $reservaInfo->codigoFoto = $request->pwsd;
            $reservaInfo->micelaneo = $request->micelaneo;
            $reservaInfo->recibo = $request->recibo;
            $reservaInfo->save();
            DB::commit();

        } catch (\Throwable $th) {
            //throw $th;
        dd($request->all(),$th);
          DB::rollBack();
        return redirect()->back()->with('message_error', 'error');
        }
      if(!env('TESTING')) {
        Mail::to($reservaInfo['email'])->send(new CorreoFinalizar($reservaInfo, $reserva));
      }

        return redirect()->back()->with('message', 'Fotografo asignado correctamente'); 
    }
    

    public function historialEliminada(Request $request)
    {
         
            $reservations = Reservations::fecha($request->fecha)->mes($request->mes)
            ->where("state",'!=',1)
            ->where("state",'!=',3)
            ->orderby('date')
            ->get();
        return view('system.reservas.historial2', compact('reservations' ));
    }

    public function historial(Request $request)
    {
        if (Auth::user()->idPerfil == 3) {
            # code...
            $reservations = Reservations::fecha($request->fecha)->mes($request->mes)
            ->where("state",3)
            ->where('idManager',Auth::user()->idUser)
            ->orderby('date')
            ->orderby('idSchedule')
            ->get();
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $trabajadores = User::where('idPerfil',3)->get();
            $reservasNumeroHoy = [];
            foreach ($trabajadores as $key => $value) {
                # code...
                $reservasNumeroHoy[$value->name] = Count(Reservations::where('idManager',$value->idUser)
                ->where('state',1)
                ->get());
            }
        }elseif(Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 ||Auth::user()->idPerfil == 8 ){
            $reservations = Reservations::fecha($request->fecha)->mes($request->mes)
            ->where("state",3)
            ->orderby('date')
            ->orderby('idSchedule')
            // ->where('idReceptionist',Auth::user()->idUser)
            ->get();
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $trabajadores = User::where('idPerfil',3)->get();
            $reservasNumeroHoy = [];
            foreach ($trabajadores as $key => $value) {
                # code...
                $reservasNumeroHoy[$value->name] = Count(Reservations::where('idManager',$value->idUser)
                ->where('state',1)
                ->get());
        // dd(Auth::user()->idPerfil,$reservations);

            }
        }else{
            $reservations = Reservations::fecha($request->fecha)->mes($request->mes)->where("state",3)
            ->where('idReceptionist',Auth::user()->idUser)
            ->orderby('date')
            ->orderby('idSchedule')
            ->get();
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $trabajadores = User::where('idPerfil',3)->get();
            $reservasNumeroHoy = [];
            foreach ($trabajadores as $key => $value) {
                # code...
                $reservasNumeroHoy[$value->name] = Count(Reservations::where('idManager',$value->idUser)
                ->where('state',1)
                ->get());
            }
        }
           
           
        // dd($trabajadores,$reservasNumeroHoy);
        
        return view('system.reservas.historial', compact('reservations','horamaxima','trabajadores','reservasNumeroHoy'));
    }
    public function ChangeHora(Request $request)
    {
        # code...
        $rules = [
            'hour' => 'required|numeric',
        
             
        ];
      
        $messages = [
            'hour.required' => "Hora requerida",
            'hour.numeric' => "Hora debe ser numerica",
            
        ];
        $this->validate($request, $rules, $messages);
        if($request->hour != null){
            $horamaxima = ParameterValues::where('idValueParameter',7)->first();
            $horamaxima->extra = $request->hour;
            $horamaxima->save();
            Session::flash('message', 'Hora global cambiada');
            return redirect()->route('reserva.index');
        }else{
            Session::flash('message_error', 'El centro seleccionado no se encuentra activo');
            return redirect()->route('reserva.index');
        }
    }

    public function create($id = null,Request $request = null)
    {
        $ids = $id;

        if ($id != null) {
          $specific = true;
          $centro = Centers::where('idCenter', $id)->where('state', 1)->first();
          if (!empty($centro)) {
            if ($request != null) {
                if ($request->edit != null) {
                  $reserva = Reservations::where('idReservation',$request->edit)->first();
                  if (empty($reserva)) {
                    return back();
                  }
                  return view('system.reservas.create', compact('centro', 'specific', 'ids','reserva'));
                }else{
                  return view('system.reservas.create', compact('centro', 'specific', 'ids'));
                }
            }else{
              return view('system.reservas.create', compact('centro', 'specific', 'ids'));
            }
          } else {
            Session::flash('message_error', 'El centro seleccionado no se encuentra activo');
            return view('system.reservas.create');
          }
        } else {
          $specific = false;
          $centros = Centers::where('state', 1)->get();
          return view('system.reservas.create', compact('centros', 'specific', 'ids'));
        }
    }

    public function edit($center, $id, Request $request  )
    {
        $ids = $id;
        if ($id != null) {
            $specific = true;
            $centro = Centers::where('idCenter', $center)->where('state', 1)->first();
            if (!empty($centro)) {
              $reserva = Reservations::where('idReservation',$id)->first();
              if (empty($reserva)) {
                  return back();
              }
              return view('system.reservas.create', compact('centro', 'specific', 'ids','reserva'));
            } else {
                Session::flash('message_error', 'El centro seleccionado no se encuentra activo');
                return view('system.reservas.create');
            }
        } else {
            $specific = false;
            $centros = Centers::where('state', 1)->get();
            return view('system.reservas.create', compact('centros', 'specific', 'ids'));
        }
    }

    public function store(Request $request)
    {
      $rules = [
        'customer' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'pax' => 'required',
        'idSchedule' => 'required',
        'num_habitacion'=>'required',
        'date' => 'required',
        'idCenter' =>'required',
        ];
      $messages = [
        'customer.required' => "El nombre es requerido",
        'email.required' => "El correo es requerido",
        'email.email' => "El correo debe estar en el formato correcto",
        'phone.required' => "El teléfono es requerido",
        'num_habitacion.required' => "El num_habitacion es requerido",
        'pax.required' => "El Pax es requerido",
        'idSchedule.required' => "El perfil es requerido",
        'idCenter.required' => "El Hotel es requerido",
        'date.required' => "Error con la fecha seleccionada",
        ];
      $this->validate($request, $rules, $messages);
      $data = $request->all();
      $center = Centers::where('idCenter',$request->idCenter)->first();
      if(empty($center)){
        return response()->json([
          'code' => 401,
          'data' => null,
          'message' => "Centro no existe",
          ]);
      }

      $scheduleCom = Schedules::where('idSchedule',$request->idSchedule)->first();
      if(empty($scheduleCom)){
        return response()->json([
          'code' => 401,
          'data' => null,
          'message' => "scheduleCom no existe",
          ]);
      }
      $date = str_replace('/', '-', $request->date);
      $formatDate = date('Y-m-d H:i:s', strtotime($date . " 00:00:00"));
      $rowToAdd = new stdClass();
      $rowToAdd->schedule = "<a href='Javascript:;' class='btn btn-primary active'>". $scheduleCom->hour.":".$scheduleCom->minute."</a>";
      $rowToAdd->client = $data['customer'];
      $rowToAdd->recepcionist = Auth::user()->name;
      $rowToAdd->pax = $data['pax'];
      if($this->validateLimitReservations($center->idCenter,$data['idSchedule'], $formatDate)) {
        return response()->json([
          'code' => 401,
          'data' => null,
          'message' => "El horario ya esta saturado, seleccione otro disponible",
        ]);
      }
      DB::beginTransaction();
      try {
        if ($request->idEdit != null) {
          $reservation = Reservations::where('idReservation',$request->idEdit)->first();
          $reservation->fill($data);
          $reservation->idManager = null;
        } else {
          $reservation = new Reservations($data);
          $reservation->idReceptionist = Auth::user()->idUser;
        }
        $reservation->date = $formatDate;
        $reservation->mes = date('m', strtotime($date . " 00:00:00"));
        $reservation->state = 1;
        $reservation->save();
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

      if(!env('TESTING')) {
        Mail::to($reservation->email)->send(new avisarCliente($reservation));
        $supervisors = User::where('state', 1)->whereIn('idPerfil', [8])->get();
        foreach ($supervisors as $value) {
          if (filled($value['email']))
            Mail::to($value['email'])->send(new avisarSupervisor2($reservation));
        }
      }

      return response()->json([
        'code' => 200,
        'data' => $rowToAdd,
        'message' => "Reservación creada correctamente"
      ]);
    }

    private function validateLimitReservations($idCenter, $idSchedule, $formatDate): bool
    {
      $totalReservations = Reservations::where('idCenter','=',$idCenter)
        ->where('idSchedule','=', $idSchedule)->where('state',1)->where('date',$formatDate)->count();
      return $totalReservations > 2;
    }

    private function sendSupervisor(User $user)
    {
        //$user->notify(new sendSupervisor);
    }
    public function asignarManager(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'idReservation' => [
                'required',
                Rule::exists('reservations', 'idReservation')->where(function ($query) {
                    return $query->where('state', 1);
                })
            ],
            'idManager' => [
                'required',
                Rule::exists('users', 'idUser')->where(function ($query) {
                    return $query->where('state', 1);
                })
            ]
        ], [
            'idReservation.required' => 'La reserva es necesaria',
            'idReservation.exists' => 'La reserva no es encontrada',
            'idManager.required' => 'El encargado es necesario',
            'idManager.exists' => 'El encargado no es encontrado',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $validate->errors()->first()
            ]);
        }

        DB::beginTransaction();
        try {
            $reserva = Reservations::where('idReservation', $request->idReservation)->first();
            $reserva->idManager = $request->idManager;
            $reserva->update();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }

        $manager = $reserva->manager;

        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $date = $reserva->date;
        $manager->day = date('d', strtotime($date));
        $manager->month = $meses[date('m', strtotime($date)) - 1];
        $manager->year = date('Y', strtotime($date));
        $manager->nameHotel = $reserva->center->name;
        $manager->fullName = $manager->name . " " . $manager->lastname;
        $manager->hour = $reserva->schedule->hour . ":" . $reserva->schedule->minute;
        $manager->notify(new notifyFotografo);

        return response()->json([
            'code' => 200,
            'data' => null,
            'message' => 'Reserva asignada correctamente'
        ]);
    }

    public function asignar(Request $request): RedirectResponse
    {
        $rules = [
            'idReserva' => 'required',
            'idFotografo' => 'required',
        ];
      |
        $messages = [
            'idReserva.required' => "La reserva es requerida",
            'idFotografo.required' => "El fotografo es requerida",
        ];
        $this->validate($request, $rules, $messages);

        DB::beginTransaction();
        $action = 'asignado';
        try {
            $reserva = Reservations::where('idReservation', $request->idReserva)->first();
            $managerAnterior = $reserva->manager;
            $reserva->idManager = $request->idFotografo;
            $reserva->update();
            DB::commit();
        } catch (\Throwable $th) {
          DB::rollBack();
          return redirect()->back()->with('message_error', 'error');
        }

        if(!env('TESTING')) {
          if ($managerAnterior != null && $managerAnterior->idUser != $reserva->idManager) {
            Mail::to($managerAnterior['email'])->send(new cancelacion($reserva));
            $action = 'reasignado';
          }
          $manager = User::find($reserva->idManager);
          if(filled($manager->email))
            Mail::to($manager->email)->send(new avisarFotografo($reserva));
        }

        return redirect()->back()->with('message', 'Fotografo '. $action .' correctamente');
    }

    public function changeState(Request $request)
    {
        $rules = [
            'idReserva' => 'required',
            'state' => 'required',
        ];
      
        $messages = [
            'idReserva.required' => "El idReserva es requerido",
            'state.required' => "El state es requerido", 
        ];
        $status = "Activo";
        $this->validate($request, $rules, $messages);
        DB::beginTransaction();
        try {
            $reserva = Reservations::where('idReservation', $request->idReserva)->first();
            $reserva->state = $request->state;
            $reserva->update();
            DB::commit();
        } catch (\Throwable $th) {
          DB::rollBack();
          return redirect()->back()->with('message_error', 'error');
        }

      if(!env('TESTING')) {
        if ($request->state == 2 && $reserva->idManager != null) {
          Mail::to($reserva->manager['email'])->send(new cancelacion($reserva));

        }
      }

      switch ($reserva->state) {
        case 1: $status = "Activa"; break;
        case 2: $status = "Cancelada"; break;
        case 3: $status = "Finalizada"; break;
        case 4: $status = "Reagendada"; break;
      }

        return redirect()->back()->with('message', 'Reservación '. $status);
    }

    public function cliente(Request $request)
    {
      $infoClient = $request;
      $idReservation = $infoClient->id;
      $reservation = Reservations::where('idReservation',$idReservation)->first();
      return view('system.reservas.clientes',compact('idReservation','reservation', 'infoClient'));
    }

    public function clientecrear(Request $request)
    {
      $rules = [
        'idReserva' => 'required',
        'email' => 'required',
        'autoriza' => 'required',
        ];

      $messages = [
        'idReserva.required' => "El idReserva es requerido",
        'email.required' => "El email es requerido",
        'autoriza.required' => "El autoriza es requerido",
        ];

      $this->validate($request, $rules, $messages);
      DB::beginTransaction();
      try {
        $reservaInfo = new ReservaInfo($request->all());
        $reservaInfo->id_reserva = $request->idReserva;
        $reservaInfo->autoriza_fotografia = $request->autoriza;
        $reservaInfo->save();
        DB::commit();
        } catch (\Throwable $th) {
          DB::rollBack();
          return redirect()->back()->with('message_error', 'error');
        }
        // Mail::to($reservaInfo['email'])->send(new CorreoFinalizar($reservaInfo,$reserva));
        return redirect()->route('reserva.index');
    }

    public function finalizar(Request $request)
    {
        $rules = [
            'idReserva' => 'required',
            'formaCobro' => 'required', 
            'montoCobrado' => 'required', 
            'micelaneo' => 'required', 
            'recibo' => 'required', 
            'link' => 'required', 
            'pwsd' => 'required', 
        ];
      
        $messages = [
            'idReserva.required' => "El idReserva es requerido",
            'formaCobro.required' => "El formaCobro es requerido", 
            'montoCobrado.required' => "El montoCobrado es requerido", 
            'micelaneo.required' => "El micelaneo es requerido", 
            'recibo.required' => "El recibo es requerido", 
            'link.required' => "El formaCobro es requerido", 
            'pwsd.required' => "El montoCobrado es requerido", 
        ];
        $this->validate($request, $rules, $messages);

        DB::beginTransaction();
        try {

            $reserva = Reservations::where('idReservation', $request->idReserva)->first();
            $reserva->state = 3;//osea falta que el admin o el otro cabron finalice
            $reserva->save();
            $reservaInfo = ReservaInfo::where('id',$reserva->receptioninfo->id)->first();
            $reservaInfo->forma_cobro = $request->formaCobro;
            $reservaInfo->total_venta = $request->montoCobrado;
            $reservaInfo->micelaneo = $request->micelaneo;
            $reservaInfo->linkPagina = $request->link;
            $reservaInfo->codigoFoto = $request->pwsd;
            $reservaInfo->recibo = $request->recibo;
            $reservaInfo->save();
            DB::commit();

        } catch (\Throwable $th) {
          DB::rollBack();
          return redirect()->back()->with('message_error', 'error');
        }

      if(!env('TESTING')) {
        Mail::to($reservaInfo['email'])->send(new CorreoFinalizar($reservaInfo, $reserva));
        $supervisors = User::where('state', 1)->whereIn('idPerfil', [8])->get();
        foreach ($supervisors as $key => $value) {
          Mail::to($value['email'])->send(new AvisarSupervisor($reservaInfo, $reserva));
        }
      }
      $monto = $request->montoCobrado * 0.21;
      $this->agregarComi(Auth::user()->idUser,"Comision por venta Fotografo", $monto);

      $montoreserva = $request->montoCobrado <= 500 ? 150 : 300;
      $this->agregarComi($reserva->idReceptionist,"Comision por venta Recepcionista",$montoreserva);

      $supervisor = User::where('idPerfil',8)->where('state',1)->get();
      foreach ($supervisor as $key => $value) {
        $this->agregarComi($value->idUser,"Comision por venta supervisor reserva",50);
      }

      return redirect()->back()->with('message', 'Reserva finalizada correctamente');
    }

    public function agregarComi($idUsuario,$tipoComi,$monto)
    {
        DB::beginTransaction();
        try {
            $comision = new comisiones();
            $comision->id_trabajador = $idUsuario;
            $comision->tipo_comision = $tipoComi;
            $comision->dia = date('d');
            $comision->mes = date('m');
            $comision->ano = date('Y');
            $comision->monto_comision = $monto;
            $comision->save();
            DB::commit();
        } catch (\Throwable $th) {
          DB::rollBack();
          return false;
        }
        return true;
    }
}