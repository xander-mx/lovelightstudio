@extends('layouts.app', ['activePage' => 'reservas.index', 'title' => 'LLS - Listado Reserva', 'navName' => 'Listado
Reserva', 'activeButton' => 'reservas'])

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

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


                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                Filtro
                            </div>
                            <div class="card-body">
                                <form action="" method="get">
                                    <div class="col">
                                        <input type="date" name="fecha" id="dateSet" class="form-control">
                                    </div>
                                    <div class="col">
                                        <select name="mes" id="" class="form-control mt-2" onchange="limpiar()">
                                            <option value="" selected>Seleccione un mes</option>
                                            <option value="1">Enero</option>
                                            <option value="2">Marzo</option>
                                            <option value="3">Enero</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                    <div class="col mt-5">
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                        <button type="reset" class="btn btn-danger" onclick="reload()">Limpiar</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col text-left">
                        <button class="btn btn-primary" onclick="exportTableToExcel('tablaprro', 'file')">Exportar
                            excel</button>
                    </div>
                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ __('Listado de reservas') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (Auth::user()->idPerfil <= 2 || Auth::user()->idPerfil == 8)
                                <table class="table table-responsive" id="tablaprro">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hotel</th>
                                            <th>Nombre Cliente</th>
                                            @if (Auth::user()->idPerfil ==1)
                                            <th>Cantidad cobrada</th>
                                                
                                            @endif
                                            <th>Miscelaneo</th>
                                            <th>Recibo</th>
                                            <th>Reservacionista</th>
                                            <th>Fotografo</th>
                                            <th>Tipo pago</th>

                                            <th>Comision Reserva</th>
                                            <th>Comision Fotografo</th>
                                            <th>Comision supervisor foto</th>
                                            <th>Comision supervisor reserva</th>

                                            <th>Emails</th>
                                            <th>Telefonos</th>
                                            <th>Canvas</th>
                                            <th>Autorizacion</th>
                                            <th>Direccion</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($reservations) > 0)
                                            @foreach ($reservations as $res)
                                                <tr>
                                                    <td>{{ date('d-m-Y', strtotime($res->date)) }}</td>
                                                    <td>{{ $res->center->name }}</td>
                                                    <td>
                                                        {{ $res->client->name . ' ' . $res->client->lastname }}

                                                    </td>
                                                    @if (Auth::user()->idPerfil ==1)
                                                    <td>
                                                        ${{ number_format($res->receptioninfo->total_venta, 2) }}
                                                        {{-- {{ $res->receptioninfo->total_venta }} --}}
                                                    </td>
                                                        
                                                    @endif
                                                    
                                                    <td>{{ $res->receptioninfo->micelaneo }}</td>
                                                    <td>{{ $res->receptioninfo->recibo }}</td>
                                                    <td>
                                                        {{ $res->receptionist->name }}
                                                    </td>
                                                    <td>
                                                        @if ($res->idManager != null)
                                                            {{ $res->manager->name }}
                                                        @else
                                                            <a href="javascript:;" class="btn btn-sm btn-warning"
                                                                onclick="openModal({{ $res->idReservation }})">Asignar</a>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ $res->receptioninfo->forma_cobro }}
                                                    </td>
                                                    <td>
                                                        @if ($res->receptioninfo->total_venta <= 500)
                                                            $150
                                                        @else
                                                            $300
                                                        @endif
                                                    </td>
                                                   
                                                    <td>
                                                        @if ($res->receptioninfo->total_venta <= 3500)
                                                            {{-- {{  ($res->receptioninfo->total_venta)*0.16}} --}}
                                                            ${{ number_format($res->receptioninfo->total_venta * 0.18, 2) }}

                                                        @else
                                                            ${{ number_format($res->receptioninfo->total_venta * 0.2, 2) }}
                                                            {{-- {{ ($res->receptioninfo->total_venta)*0.20 }} --}}

                                                        @endif
                                                    </td>
                                                    <td>
                                                        ${{ number_format($res->receptioninfo->total_venta * 0.015, 2) }}
                                                    </td>
                                                    <td>
                                                        $50
                                                    </td>
                                                    <td>
                                                        @if ($res->receptioninfo->email != null)
                                                            {{ $res->receptioninfo->email }}
                                                        @endif
                                                        @if ($res->receptioninfo->email != null)
                                                           <br> {{ $res->receptioninfo->email2 }}
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($res->receptioninfo->telefono1 != null)
                                                            {{ $res->receptioninfo->telefono1 }}
                                                        @endif
                                                        @if ($res->receptioninfo->telefono2 != null)
                                                           <br> {{ $res->receptioninfo->telefono2 }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($res->receptioninfo->canva != null)
                                                            {{ $res->receptioninfo->canva  }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    <td>
                                                       
                                                        {{ $res->receptioninfo->autoriza_fotografia  }}

                                                    </td>
                                                    <td>
                                                       
                                                        @if ($res->receptioninfo->direccion != null)
                                                        {{ $res->receptioninfo->direccion  }}
                                                    @else
                                                        -
                                                    @endif

                                                    </td>
                                                  
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="17">No se encuentran registos de Reservas</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>


                            @endif

                            @if (Auth::user()->idPerfil > 2 && Auth::user()->idPerfil != 8)
                                <table class="table table-responsive" id="tablaprro">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Hotel</th>
                                            <th>Nombre Cliente</th>
                                            <th>Comision Reserva</th>
                                            <th>Comision Fotografo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($reservations) > 0)
                                            @foreach ($reservations as $res)
                                                <tr>
                                                    <td>{{ $res->idReservation }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($res->date)) }}</td>
                                                    <td>{{ $res->center->name }}</td>
                                                    <td>
                                                        {{ $res->client->name . ' ' . $res->client->lastname }}
                                                    </td>
                                                    <td>
                                                        @if (Auth::user()->idUser == $res->idReceptionist)
                                                            @if ($res->receptioninfo->total_venta <= 500)
                                                                $150
                                                            @else
                                                                $300
                                                            @endif
                                                        @else
                                                            -
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if (Auth::user()->idUser == $res->idManager)
                                                            @if ($res->receptioninfo->total_venta <= 3500)
                                                                {{-- {{  ($res->receptioninfo->total_venta)*0.16}} --}}
                                                                ${{ number_format($res->receptioninfo->total_venta * 0.18, 2) }}
                                                            @else
                                                                ${{ number_format($res->receptioninfo->total_venta * 0.20 , 2) }}
                                                                {{-- {{ ($res->receptioninfo->total_venta)*0.20 }} --}}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9">No se encuentran registos de Reservas</td>
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


@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
    </script>

    <script>
        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }

        function limpiar() {
            document.getElementById('dateSet').value = "";
        }

        function reload() {
            window.location.href = "{!! route('reserva.historial') !!}"
        }
    </script>

@endsection
