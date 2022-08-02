@extends('layouts.app', ['activePage' => 'reservas.index', 'title' => 'LLS - Listado Reserva', 'navName' => 'Listado
Reserva', 'activeButton' => 'reservas'])

@section('style')
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
        
                
         
                <div class="row">
                    <div class="col text-left">
                        <button class="btn btn-primary" onclick="exportTableToExcel('tablaprro', 'file')">Exportar excel</button> 
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
                            @if (Auth::user()->idPerfil <= 2  ||Auth::user()->idPerfil == 8 )
                                <table class="table table-responsive" id="tablaprro">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hotel</th>

                                            <th>Nombre Cliente</th>
                                            <th>Recepcionista</th>

                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($reservations) > 0)
                                            @foreach ($reservations as $res)
                                                <tr>
                                                    <td>{{ date('d-m-Y', strtotime($res->date)) }}</td>
                                                    <td>{{ $res->center->name }}</td>
                                                    
                                                    <td>
                                                        {{ $res->customer }}
                                                    </td>
                                                    <td>
                                                        {{ $res->receptionist->name . ' ' . $res->receptionist->lastname }}
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
                                                                <span class="badge badge-danger">Eliminado</span>
                                                        @endswitch
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>
    function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
function limpiar() {
    document.getElementById('dateSet').value="";
}

function reload() {
    window.location.href ="{!! route('reserva.historial') !!}"
}
</script>

@endsection
