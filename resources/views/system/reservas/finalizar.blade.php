@extends('layouts.app', ['activePage' => 'reservas.index', 'title' => 'LLS - Listado Reserva', 'navName' => 'Listado
Reserva', 'activeButton' => 'reservas'])

@section('stylebb')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
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
                
             
             
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ __('Listado de reservas por Falta de finalizar') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Auth::user()->idPerfil <= 2)
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
                                                <td>
                                                    {{ $res->num_habitacion }}

                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($res->date)) }}</td>
                                                <td>{{ $res->schedule->hour }}: {{ $res->schedule->minute }}
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
                                                            <span class="badge badge-danger">Falta Finalizar</span>
                                                    @endswitch
                                                </td>

                                                <td>
                                                    @if ($res->receptioninfo != null)
                                                    <a href="javascript:;" onclick="openModalFinalizar({{ $res->idReservation }})" class="btn btn-info">Finalizar</a>
                                                    @else
                                                        <a href="{{ route('reserva.cliente') }}?reserva={{ $res->idReservation }}" target="_blank" class="btn btn-primary">Ver formulario Cliente</a>
                                                    @endif
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->idPerfil < 2 )
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
                        <form action="{{ route('reserva.supervisor.finalizar.post') }}" method="post">
                            @csrf
                            <input type="hidden" name="idReserva" id="idReservaFina">
                            
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Link</label>
                                <input type="text" min="0" class="form-control" id="exampleFormControlInput1" placeholder="" name="link" required> 
                              </div>
                              <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Password</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="" name="pwsd" required> 
                              </div>
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                              </div>
                        </form>
                    </div>
                </div>

            </div>

         
            
        </div>
    </div>
</div>
@endif



@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>

    @if (Auth::user()->idPerfil < 2)
        
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
