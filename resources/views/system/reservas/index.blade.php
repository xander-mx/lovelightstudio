@php
  use \App\Http\Controllers\Admin\ReservaController;
@endphp

@extends('layouts.app', ['activePage' => 'reservas.index', 'title' => 'LLS - Listado Reserva', 'navName' => 'Listado
Reserva', 'activeButton' => 'reservas'])

@section('stylebb')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        .modal-backdrop {
            position: initial;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: #000;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                @if (Session::has('message'))
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                😉 {{ Session::get('message') }} 😉
                            </div>
                        </div>
                    </div>
                @endif
                @if (Session::has('message_error'))
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                😓 {{ Session::get('message_error') }} 😒
                            </div>
                        </div>
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <p> {{ trans('restaurantes.corregirErrores') }} </p>
                                <ul>
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col">
                        <h3>Sesiones  Activas: {{ Count($reservations) }}</h3>
                    </div>
                </div>
            
                @if (Auth::user()->idPerfil == 1)
                    <div class="row">
                        <form action="{{ route('reserva.ChangeHora') }}" method="post">
                            <div class="card col-md-12">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="cellphone">Hora maxima de reserva</label>
                                                <input type="number" name="hour" id="cellphone" class="form-control"
                                                    required value="{{ $horamaxima->extra }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary">Change</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
             
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">
                    <div class="card col-md-12">
                        <div class="card-body">
                            @if (Auth::user()->idPerfil <= 2 ||Auth::user()->idPerfil == 8 )
                                @include('templates.datatable', [
                                  'tableId' => 'admin-table',
                                  'title' => 'Listado de Reservas',
                                  'headers' => ReservaController::$indexHeaders,
                                  'fields' => ReservaController::$indexFields,
                                  'urlTable' => route(ReservaController::$routeIndexTable)])
                            @else
                                @switch(Auth::user()->idPerfil)
                                    @case(3)
                                        <table class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Hotel</th>
                                                    <th>Nombre</th>
                                                    <th>Habitacion</th>
                                                    <th>Pax</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($reservations) > 0)
                                                    @foreach ($reservations as $res)
                                                        <tr>

                                                            <td>{{ date('d-m-Y', strtotime($res->date)) }}</td>
                                                            <td>{{ $res->schedule->hour }}: {{ $res->schedule->minute }}
                                                            </td>
                                                        </tr>
                                                        <td>
                                                            {{ $res->center->name }}
                                                        </td>
                                                            <td>
                                                                {{ $res->client->name . ' ' . $res->client->lastname }}
                                                           
                                                            <td>
                                                                {{ $res->num_habitacion }}

                                                            </td>
                                                            
                                                            <td>
                                                                {{ $res->pax }}
                                                            </td>
                                                            <td>
                                                                @switch($res->state)
                                                                    @case(1)
                                                                        <span class="badge badge-success">Activo</span>
                                                                    @break
                                                                    @case(2)
                                                                        <span class="badge badge-warning">Cancelado</span>
                                                                    @break
                                                                    @case(3)
                                                                        <span class="badge badge-success">Finalizado</span>
                                                                    @break
                                                                    @default
                                                                        <span class="badge badge-danger">Re-Agendado</span>
                                                                @endswitch
                                                            </td>

                                                            <td>
                                                                @if ($res->receptioninfo != null)
                                                                <a href="javascript:;" onclick="openModalFinalizar({{ $res->idReservation }})" class="btn btn-info">Finalizar</a>
                                                                @else
                                                                    <a href="{{ route('reserva.cliente') }}?reserva={{ $res->idReservation }}" target="_blank" class="btn btn-primary">Ver formulario Cliente</a>
                                                                @endif
                                                        <a href="javascript:;" class="btn btn-sm btn-warning" onclick="openAction({{ $res->idReservation }},{{ $res->idCenter }})">Acciones</a>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8">No se encuentran registos de Reservas</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    @break
                                @case(4)
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Hotel</th>
                                                <th>Habitacion</th>
                                                <th>Fecha</th>
                                                <th>Hora</th>
                                                <th>Pax</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($reservations) > 0)
                                                @foreach ($reservations as $res)
                                                    <tr>
                                                        <td>
                                                            {{ $res->client->name . ' ' . $res->client->lastname }}
                                                        </td>
                                                        <td>
                                                            {{ $res->center->name }}
                                                        </td>
                                                        <td>{{ $res->num_habitacion }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($res->date)) }}</td>
                                                        <td>{{ $res->schedule->hour }}: {{ $res->schedule->minute }}</td>
                                                        <td>{{ $res->pax }}</td>
                                                        <td>
                                                            @switch($res->state)
                                                                @case(1)
                                                                    <span class="badge badge-success">Activo</span>
                                                                @break
                                                                @case(2)
                                                                    <span class="badge badge-warning">Cancelado</span>
                                                                @break
                                                                @case(3)
                                                                    <span class="badge badge-success">Finalizado</span>
                                                                @break
                                                                @default
                                                                    <span class="badge badge-danger">Re-Agendado</span>
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" class="btn btn-sm btn-warning" onclick="openAction({{ $res->idReservation }},{{ $res->idCenter }})">Acciones</a>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7">No se encuentran registros de Reservas</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @break
                                @default

                            @endswitch
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->idPerfil == 3)
<div class="modal" tabindex="-1" role="dialog" id="myModalFinalizar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formato  finalizacion de Reserva </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                onclick="closeModalFinalizar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <strong>Por favor ingrese los campos para poder enviar los datos del cliente</strong>
                    </div>
                </div>

                <div class="row">
                    
                    <div class="col">
                        <form action="{{ route('reserva.finalizar') }}" method="post">
                            @csrf
                            <input type="hidden" name="idReserva" id="idReservaFina">
                            
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Monto cobrado</label>
                                <input type="number" min="0" class="form-control" id="exampleFormControlInput1" placeholder="" name="montoCobrado" required> 
                              </div>
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Forma de cobro</label>
                                {{-- <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="" name="formaCobro" required>  --}}
                                <select name="formaCobro" id="" class="form-control">
                                    <option value="Tarjeta" selected>Tarjeta</option>
                                    <option value="Efectivo" >Efectivo</option>
                                </select>

                              </div>
                             
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">N° Miscelaneo</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="" name="micelaneo" required> 
                              </div>

                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Recibo</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="" name="recibo" required> 
                              </div>
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Link</label>
                                <input type="text" min="0" class="form-control" id="exampleFormControlInput1" placeholder="" name="link" required> 
                              </div>
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Password</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="" name="pwsd" required> 
                              </div>
                           
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-3">Enviar!!</button>
                              </div>
                        </form>
                    </div>
                </div>

            </div>

            {{-- <div class="modal-footer">
                <div class="row text-center">
                    <div class="col text-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeModalFinalizar()">Cerrar</button>
                    </div>
                </div>
            </div> --}}
            
        </div>
    </div>
</div>
@endif

{{-- Dialog aignar usuario --}}
@if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 || Auth::user()->idPerfil == 8 || Auth::user()->idPerfil == 3 || Auth::user()->idPerfil == 4)
    <div class="modal" tabindex="-1" role="dialog" id="myModal2"> 
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Acciones </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="closeAction()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- <div class="col">
                            <form action="{{ route('reserva.changeState') }}" method="post">
                                @csrf
                                <input type="hidden" name="idReserva" id="reservaCan_1">
                                <input type="hidden" name="state" value="1">
                                <button class="btn btn-success">Activar</button>
                            </form>
                        </div> --}}
                        <div class="col">
                            <form action="{{ route('reserva.changeState') }}" method="post">
                                @csrf
                                <input type="hidden" name="idReserva" id="reservaCan_2">
                                <input type="hidden" name="state" value="2">
                                <button class="btn btn-danger">Cancelar</button>
                            </form>
                        </div>
                        <div class="col">
                                <a class="btn btn-info" href=""  id="linssk">Reagendar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('templates.modal', ['modalId' => 'myModal', 'title' => 'Asignar Fotografo', 'backdrop' => true])
        @slot('body')
            <div class="row">
                <div class="col">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Nombre fotografo</th>
                            <th>Nº Sesion </th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($trabajadores  as $key => $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $reservasNumeroHoy[$item->idUser] }}</td>
                                <td>
                                    <form action="{{ route('reserva.asignar') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="idReserva" id="reserva_{{ $key }}">
                                        <input type="hidden" name="idFotografo" value="{{ $item->idUser }}">
                                        <button type="submit" class="btn btn-primary">Asignar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endslot
        @slot('footer')
            <div class="row text-center">
                <div class="col text-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarModal()">Cerrar</button>
                </div>
            </div>
        @endslot
    @endcomponent
@endif

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>

    @if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 ||Auth::user()->idPerfil == 8||Auth::user()->idPerfil == 3 || Auth::user()->idPerfil == 4)
        var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
        keyboard: false
        });
        var foto =JSON.parse( '{!! $trabajadores !!}')
        var myModal2 = new bootstrap.Modal(document.getElementById('myModal2'), {
        keyboard: false
        });
        var url ="";

        function openAction(id,center =1) {
            url ="/administrator/reserva/edit/"+center+"?edit="+id;
            // reservaCan_1
            document.getElementById("linssk").href = url;

            // document.getElementById('reservaCan_1').value= id;
            document.getElementById('reservaCan_2').value= id;
            @if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 || Auth::user()->idPerfil == 8  )
            // document.getElementById('reservaCan_3').value= id;

                       @endif
            myModal2.show();
        }
        function closeAction() {
            myModal2.hide();
            
        }
            function openModal(id) {
               
                for (let index = 0; index < foto.length; index++) {
                    document.getElementById('reserva_'+index).value= id;
                    myModal.show();                    
                }
                // console.log(foto.length);
            }

            function cerrarModal( ) {
                myModal.hide()                   
                
            }
    @endif
    @if (Auth::user()->idPerfil == 3)
        
        var myModal = new bootstrap.Modal(document.getElementById('myModalFinalizar'), {
        keyboard: false
        });

        function openModalFinalizar(id) {
            document.getElementById('idReservaFina').value = id;
            myModal.show();
        }
        function closeModalFinalizar() {
            myModal.hide();
            
        }
    @endif
</script>
@endsection
