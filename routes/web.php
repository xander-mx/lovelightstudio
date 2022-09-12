<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\GiftsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ReservaController;
use App\Http\Controllers\Admin\ReservesSoldController;
use App\Http\Controllers\Admin\ServicesController;
use App\Models\Services;
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

Route::get('/reserva/cliente/information', [ReservaController::class, 'cliente'])->name('reserva.info');
Route::post('/reserva/cliente', [ReservaController::class, 'clientecrear'])->name('reserva.cliente.post');
Route::get('/events/{id}/canvas/{code}', [EventsController::class, 'canvas'])->name('weddings.events.canvas');
Route::post('/gifts/save', [GiftsController::class, 'save'])->name('weddings.gifts.save');

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
      Route::get('/edit/{center}/{id}', [ReservaController::class, 'edit'])->name('reserva.edit');
      Route::post('/create/ChangeHora', [ReservaController::class, 'ChangeHora'])->name('reserva.ChangeHora');
      Route::post('/store', [ReservaController::class, 'store'])->name('reserva.store');
      Route::post('/asignar', [ReservaController::class, 'asignar'])->name('reserva.asignar');
      Route::post('/changeState', [ReservaController::class, 'changeState'])->name('reserva.changeState');
      Route::post('/finalizar', [ReservaController::class, 'finalizar'])->name('reserva.finalizar');
      Route::get('/finalizar/supervisor', [ReservaController::class, 'reservaFinalizada'])->name('reserva.supervisor.finalizar');
      Route::post('/finalizar/supervisor', [ReservaController::class, 'finalizarSupervisor'])->name('reserva.supervisor.finalizar.post');

      Route::get('/sold/table', [ReservesSoldController::class, 'loadTable'])->name('reserves.sold.table');
      Route::get('/sold/index', [ReservesSoldController::class, 'index'])->name('reserves.sold.index');
    });

    Route::group(['prefix' => 'ajax'], function () {
        Route::get('horario/day', [Admin\ScheduleController::class, 'ajaxHorarios'])->name('ajax.horarios');
    });

    Route::group(['prefix' => 'weddings'], function() {
      Route::get('/services/index', [ServicesController::class, 'index'])->name('weddings.services.index');
      Route::get('/services/table', [ServicesController::class, 'loadTable'])->name('weddings.services.table');
      Route::get('/services/information/{id}', [ServicesController::class, 'getInformation'])->name('weddings.services.information');
      Route::delete('/services/delete/{id}', [ServicesController::class, 'delete'])->name('weddings.services.delete');
      Route::post('/services/save', [ServicesController::class, 'save'])->name('weddings.services.save');

      Route::get('/events/index', [EventsController::class, 'index'])->name('weddings.events.index');
      Route::get('/events/table', [EventsController::class, 'loadTable'])->name('weddings.events.table');
      Route::get('/events/{id}/table', [EventsController::class, 'loadPaymentsTable'])->name('weddings.events.payments.table');
      Route::get('/events/edit/{id}', [EventsController::class, 'getInformation'])->name('weddings.events.information');
      Route::get('/events/autocomplete/{search}', [EventsController::class, 'autocomplete'])->name('weddings.events.autocomplete');
      Route::post('/events/save', [EventsController::class, 'save'])->name('weddings.events.save');
      Route::post('/events/links/{id}', [EventsController::class, 'saveLinks'])->name('weddings.events.links');
      Route::post('/events/video/{id}', [EventsController::class, 'saveVideo'])->name('weddings.events.video');
      Route::delete('/events/delete/{id}', [EventsController::class, 'delete'])->name('weddings.events.delete');
      Route::put('/events/finalize/{id}', [EventsController::class, 'finalize'])->name('weddings.events.finalize');
      Route::put('/events/cancel/{id}', [EventsController::class, 'cancel'])->name('weddings.events.cancel');

      Route::get('/payments/table', [PaymentsController::class, 'loadTable'])->name('weddings.payments.table');
      Route::get('/payments/index', [PaymentsController::class, 'index'])->name('weddings.payments.index');
      Route::get('/payments/edit/{id}', [PaymentsController::class, 'getInformation'])->name('weddings.payments.information');
      Route::post('/payments/save', [PaymentsController::class, 'save'])->name('weddings.payments.save');
      Route::delete('/payments/delete/{id}', [PaymentsController::class, 'delete'])->name('weddings.payments.delete');

      Route::get('/gifts/{id}', [GiftsController::class, 'getGiftsInfo'])->name('weddings.gifts.obtain');
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
