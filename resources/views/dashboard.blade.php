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
        <div class="row ">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="col text-center">
                            <strong>Cantidad de sesiones reservadas en la quincena</strong><br>
                            <h2 class="text-center" style="color: green;">{{ $reservas }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->idPerfil == 3)
            <div class="row ml-5">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="col text-center">
                                <strong>Cantidad de sesiones Fotográfica en la quincena</strong><br>
                                <h2 class="text-center" style="color: green;">{{ $reservasFotografo }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->idPerfil == 1)
            <div class="row ml-5">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Hotel</th>
                                        <th>Sesiones</th>
                                        <th>Total vendido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($center as $res)
                                        <tr>
                                            <td>{{ $res->name }}</td>
                                            <td>{{ $numRese[$res->idCenter] }}</td>
                                            <td> ${{ number_format($totalreseCenter[$res->idCenter], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td>${{ number_format($totalFinalCentros, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



        @endif

        @if (Auth::user()->idPerfil <= 2 || Auth::user()->idPerfil == 8)
            <div class="row ml-5">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Trabajadores</th>
                                        <th>Sesiones</th>
                                        @if (Auth::user()->idPerfil == 1)
                                            <th>Total vendido</th>
                                            <th>Total comisiones</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trabajadAdmin as $res)
                                        <tr>
                                            <td>{{ $res->name }}</td>
                                            <td>{{ $numReseTrabaAdmin[$res->idUser] }}</td>
                                            @if (Auth::user()->idPerfil == 1)
                                                <td> ${{ number_format($totalReseTrabajador[$res->idUser], 2) }}
                                                </td>
                                                <td>
                                              ${{ number_format($sumaTrabajadores[$res->idUser], 2) }}
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->idPerfil != 1)
            <div class="row ml-5">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Tipo de comisión</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($comisionesA) > 0)
                                        @foreach ($comisionesA as $res)
                                            <tr>
                                                <td>{{ $res->tipo_comision }}</td>
                                                <td> ${{ number_format($res->monto_comision, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td> ${{ number_format($quincena, 2) }} </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2">No se encuentran registros de Reservas</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row ml-5">
            <div class="col">
             <div class="card">
                 <div class="card-body">
                     <div class="col text-center">
                         <strong>Ganancias de la quincena</strong><br>
                         <h2 class="text-center" style="color: green;">
                            ${{ number_format((($quincena)), 2) }}    
                        </h2>
                         

                     </div>
                 </div>
             </div>
            </div>
         </div> --}}
        @endif
        {{-- <img src="{{ asset('assets/img/apple-icon.png') }}" alt="" srcset="" width="200px" id="watermark"> --}}
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // Javascript method's body can be found in assets/js/demos.js


        });
    </script>
@endpush
