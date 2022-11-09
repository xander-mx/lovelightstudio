@extends('layouts.app', ['activePage' => 'dashboard', 'title' => 'Dashboard', 'navName' => 'Dashboard', 'activeButton'
=> 'laravel'])

@section('content')
    <style>
        #watermark {
            position: fixed;
            bottom: 5px;
            right: 5px;
            opacity: 0.5;
            z-index: 99;
            color: white;
        }

    </style>
    <div class="content">
        <div class="row ">
            <div class="col-6 text-center text-primary">
                <h1>{{ Auth::user()->name }} {{ Auth::user()->lastname }}</h1>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4 class="lbl-dashboard"><strong>Número de Seguro Social </strong>{{ Auth::user()->seguro_social }}</h4>
                                <h4 class="lbl-dashboard"><strong>Curp </strong> {{ Auth::user()->curp }}</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4 class="text-center"><strong>Sesiones Vendidas</strong><br>{{ Auth::user()->curp }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <strong>Sesiones en la quincena</strong>
                <div class="row justify-content-center">
                    <div class="card col-lg-3 col-md-12 col-sm-12 text-white bg-warning ml-2 mr-2">
                        <div class="card-header text-center font-weight-bold">Reservadas</div>
                        <div class="card-body">
                            <p class="card-text text-center font-weight-bold">{{ $actives }}</p>
                        </div>
                    </div>
                    <div class="card col-lg-3 col-md-12 col-sm-12 text-white bg-success ml-2 mr-2">
                        <div class="card-header text-center font-weight-bold">Vendidas</div>
                        <div class="card-body">
                            <p class="card-text text-center font-weight-bold">{{ $sold }}</p>
                        </div>
                    </div>
                    <div class="card col-lg-3 col-md-12 col-sm-12 text-white bg-primary ml-2 mr-2">
                        <div class="card-header text-center font-weight-bold">Total Otros</div>
                        <div class="card-body">
                            <p class="card-text text-center font-weight-bold">*</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <div class="col">
                            <strong>Comisiones en Quincena</strong><br>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-responsive">
                                        <thead>
                                        <tr>
                                            @if(Auth::user()->isAdmin()) <th>Empleado</th> @endif
                                            @if(!Auth::user()->isAdmin())<th>Concepto</th> @endif
                                            <th>Comisión</th>
                                            <th>Estado</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($commissions as $commission)
                                            <tr>
                                                @if(Auth::user()->isAdmin())
                                                    <td class="text-left">{{$commission->employeeData->name}}</td>
                                                @endif
                                                @if(!Auth::user()->isAdmin())
                                                    <td class="text-left">{{$commission->concept}}</td>
                                                @endif
                                                <td class="text-left">${{ number_format(
                                                    Auth::user()->isAdmin() ? $commission->total :
                                                    $commission->amount,2)}}</td>
                                                <td>{{$commission->status}}</td>
                                            </tr>
                                        @endforeach
                                        @if($commissions->count() == 0)
                                            <tr class="odd">
                                                <td colspan="3" class="dataTables_empty">No hay registros para mostrar</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
