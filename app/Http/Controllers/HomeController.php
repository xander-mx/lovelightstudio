<?php

namespace App\Http\Controllers;

use App\Facades\ConfigurationsFacade;
use App\Models\Centers;
use App\Models\comisiones;
use App\Models\Commissions;
use App\Models\Payrolls;
use App\Models\Reservations;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $lastDatePayroll = Payrolls::where('status', 'PAGADO')->max('closeDate');
      $initDate = filled($lastDatePayroll) ? Carbon::parse($lastDatePayroll)->format('Y-m-d') : $lastDatePayroll;
      $initDate = $initDate ?? Carbon::now()->startOfMonth()->format('Y-m-d');
      $endDate = ConfigurationsFacade::closeDate();
      $reservations = Reservations::whereBetween('date', [$initDate, $endDate->value])
        ->when(Auth::user()->isPhotographer(),function($query) {
          $query->where('idManager', '=', Auth::user()->idUser);
        })->get();
      $actives = $reservations->count();
      $sold = $reservations->where('state', 3)->count();
      $commissions = Commissions::with('employeeData')->whereBetween('date', [$initDate, $endDate->value])
        ->when(Auth::user()->isPhotographer(), function($query) {
          $query->where('employee', Auth::user()->idUser);
        })->when(Auth::user()->isAdmin(), function($query) {
          $query->addSelect('employee', DB::raw('sum(amount) as total'))->groupBy('employee');
        })->get();

        $totalFinalCentros = 0;
        $mesHoy = date('m');
        $ano = date('Y');
        $sumaTrabajadores = [];
        $diaHoy = date('d');
        $diaInicial = 0;
        $diaFinal = 0;
        if ($diaHoy < 15) {
            $diaFinal = 15;
        } else {
            $diaInicial = 15;
            $diaFinal = 30;
        }
        $quincena = 0;
        $comisionesA = comisiones::where('mes', $mesHoy)
            ->where('id_trabajador', Auth::user()->idUser)
            ->where('state', 1)
            ->get();
        foreach ($comisionesA as $key => $value) {
            $quincena = $quincena + $value->monto_comision;
        }
        $fechaInicial = $ano . '-' . $mesHoy . '-' . $diaInicial . ' 00:00:00';
        $fechaFinal = $ano . '-' . $mesHoy . '-' . $diaFinal . ' 00:00:00';
        $reservas = Count(
            Reservations::where('idReceptionist', Auth::user()->idUser)
                ->where('date', '>=', $fechaInicial)
                ->where('date', '<=', $fechaFinal)
                ->where('state', '!=', 2)
                ->get()
        );
        $reservasFotografo = Count(
            Reservations::where('idManager', Auth::user()->idUser)
                ->where('date', '>=', $fechaInicial)
                ->where('date', '<=', $fechaFinal)
                ->where('state', '!=', 2)
                ->get()
        );
        $center = null;
        $numRese = [];
        $totalreseCenter = [];
        if (Auth::user()->idPerfil == 1) {
            # code...
            $center = Centers::all();
            // dd($center);
            $totalFinalCentros = 0;
            foreach ($center as $key => $value) {
                # code...
                $reserva33s = Reservations::where('mes', $mesHoy)
                    ->where('idCenter', $value->idCenter)
                    ->where('state', 3)
                    ->get();
                $numRese[$value->idCenter]  = Count($reserva33s);
                $totalCenter = 0;
                foreach ($reserva33s as $key => $value3) {
                    # code...
                    $totalCenter = $totalCenter + intval($value3->receptioninfo->total_venta);
                }
                $totalreseCenter[$value->idCenter] = $totalCenter;
                $totalFinalCentros += $totalCenter;
            }
        }
        $trabajadAdmin = null;
        $numReseTrabaAdmin = [];
        $totalReseTrabajador = [];
        if (Auth::user()->idPerfil <= 2 || Auth::user()->idPerfil == 8) {
            if (Auth::user()->idPerfil == 1) {
                # code...
            $trabajadAdmin = User::where('idPerfil','!=', 1)
            ->where('idPerfil','!=', 6)
            ->where('state', 1)->get();

            } else {
                # code...
            $trabajadAdmin = User::where('idPerfil',3)->where('state', 1)->get();

            }
            
            $sumaTrabajadores = [];

            foreach ($trabajadAdmin as $key => $value) {
                # code...

                if ( $value->idPerfil == 3) {
                    # code...
                    $reserva3343s = Reservations::where('mes', $mesHoy)
                    ->where('idManager', $value->idUser)
                    ->where('state', 3)
                    ->get();
                } else {
                    # code...
                    $reserva3343s = Reservations::where('mes', $mesHoy)
                    ->where('idReceptionist', $value->idUser)
                    ->where('state', 3)
                    ->get();
                }
                
              
                $totalComisionTrabajador = 0;
                $comisionesTrabajadoress = comisiones::where('id_trabajador', $value->idUser)->where('state', 1)->get();
                foreach ($comisionesTrabajadoress as    $comisionnnn) {
                    $totalComisionTrabajador = $totalComisionTrabajador + $comisionnnn->monto_comision * 1;
                }
                $sumaTrabajadores[$value->idUser] =$totalComisionTrabajador;
                $numReseTrabaAdmin[$value->idUser] = Count($reserva3343s);
                $totallocal = 0;
                foreach ($reserva3343s as $key => $value2) {
                    $totallocal = $totallocal + intval($value2->receptioninfo->total_venta);
                }
                $totalReseTrabajador[$value->idUser] = $totallocal;
            }
        }

        return view('dashboard', compact(
            'quincena',
            'reservas',
            'reservasFotografo',
            'comisionesA',
            'center',
            'numRese',
            'totalreseCenter',
            'trabajadAdmin',
            'numReseTrabaAdmin',
            'totalReseTrabajador',
            'totalFinalCentros',
            'sumaTrabajadores',
          'actives',
          'sold',
          'commissions'
        ));
    }
}
