<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Admin\ReservaController;
use App\Models\User;
use App\Notifications\newReserva;
use App\Notifications\sendSupervisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check())
    {
        return redirect('administrator');
    }else{
        return view('auth.login');
    }
});
Route::get('/reserva/cliente', [Admin\ReservaController::class, 'cliente'])->name('reserva.cliente');
Route::post('/reserva/cliente', [Admin\ReservaController::class, 'clientecrear'])->name('reserva.cliente.post');

Auth::routes(['excepts' => 'register']);

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function () {

    Route::get('/', function(){
        return redirect()->route('home');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'trabajadores'], function(){
        Route::get('/', [Admin\TrabajadorController::class, 'index'])->name('trabajador.index');
        Route::get('/create', [Admin\TrabajadorController::class, 'create'])->name('trabajador.create');
        Route::get('/editt', [Admin\TrabajadorController::class, 'editt'])->name('trabajador.editt');
        Route::post('/create', [Admin\TrabajadorController::class, 'store'])->name('trabajador.store');
        Route::post('/put', [Admin\TrabajadorController::class, 'put'])->name('trabajador.puti');
        Route::post('/change', [Admin\TrabajadorController::class, 'change'])->name('trabajador.change');
        Route::get('/sueldo', [Admin\SueldosController::class, 'index'])->name('sueldo.index');
        Route::post('/sueldo', [Admin\SueldosController::class, 'CrearComi'])->name('sueldo.create');
        Route::get('/sueldo/limpiar', [Admin\SueldosController::class, 'limpiar'])->name('sueldo.limpiar');
        Route::post('/eliminar', [Admin\SueldosController::class, 'changeState'])->name('sueldo.eliminar');

    });

    Route::group(['prefix' => 'reserva'], function() {
        Route::get('/', [ReservaController::class, 'index'])->name('reserva.index');
        Route::get('/table', [ReservaController::class, 'loadTable'])->name('reserva.table');
        Route::get('/finalizar', [ReservaController::class, 'index'])->name('reserva.finarlizar');
        Route::get('/historial', [ReservaController::class, 'historial'])->name('reserva.historial');
        Route::get('/historial/eliminada', [ReservaController::class, 'historialEliminada'])->name('reserva.historial.eliminada');
        Route::get('/create/{id?}', [ReservaController::class, 'create'])->name('reserva.create');
        Route::get('/edit/{id}', [ReservaController::class, 'edit'])->name('reserva.edit');
        Route::post('/create/ChangeHora', [ReservaController::class, 'ChangeHora'])->name('reserva.ChangeHora');
        Route::post('/store', [ReservaController::class, 'store'])->name('reserva.store');
        Route::post('/asignar', [ReservaController::class, 'asignar'])->name('reserva.asignar');
        Route::post('/changeState', [ReservaController::class, 'changeState'])->name('reserva.changeState');
        Route::post('/finalizar', [ReservaController::class, 'finalizar'])->name('reserva.finalizar');
        Route::get('/finalizar/supervisor', [ReservaController::class, 'reservaFinalizada'])->name('reserva.supervisor.finalizar');
        Route::post('/finalizar/supervisor', [ReservaController::class, 'finalizarSupervisor'])->name('reserva.supervisor.finalizar.post');
    });

    Route::group(['prefix' => 'ajax'], function () {
        Route::get('horario/day', [Admin\ScheduleController::class, 'ajaxHorarios'])->name('ajax.horarios');
    });

    Route::get('/notification', function () {
        $invoice = User::find(1);
        $invoice->day = "12";
        $invoice->month = "Enero";
        $invoice->year = "2021";
        $invoice->site = "Patio";
        $invoice->hour = "08:00";
        $invoice->nameHotel = "Hotelas";
        return (new sendSupervisor($invoice))
                ->toMail($invoice);
    });
});
