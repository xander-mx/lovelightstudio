@extends('layouts.app', ['activePage' => 'sueldo', 'title' => 'LLS - Listado Sueldos', 'navName' => 'Listado
Sueldos', 'activeButton' => 'reservas'])

@section('stylebb')
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
                        <div class="card">
                            <div class="card-header">
                                Filtro
                            </div>
                            <div class="card-body">
                                <form action="" method="get">
                                    <div class="col">
                                        <input type="number" name="ano" placeholder="año"   class="form-control">
                                    </div>
                                    <div class="col">
                                        <select name="mes" id="" class="form-control mt-2" >
                                            <option value="" selected >Seleccione un mes</option>
                                            <option value="1" >Enero</option>
                                            <option value="2" >Marzo</option>
                                            <option value="3" >Enero</option>
                                            <option value="4" >Abril</option>
                                            <option value="5" >Mayo</option>
                                            <option value="6" >Junio</option>
                                            <option value="7" >Julio</option>
                                            <option value="8" >Agosto</option>
                                            <option value="9" >Septiembre</option>
                                            <option value="10" >Octubre</option>
                                            <option value="11" >Noviembre</option>
                                            <option value="12" >Diciembre</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div class="mt-1">
                                            {{-- <label for="exampleFormControlInput1" class="form-label">Trabajador</label> --}}
                                            <select name="trabajador" class="form-control" id="">
                                                @foreach ($trabajadores as $item)
                                                    <option value="{{ $item->idUser }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                        <div class="col">
                            <button class="btn btn-primary" onclick="openModalFinalizar()">+</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary" onclick="exportTableToExcel('tablaprro', 'file')">Exportar
                                excel</button>
                        </div>
                        <div class="col">
                            <div class="col">
                                <button class="btn btn-primary" onclick="openModalFinalizar2()">Reset</button>
                            </div>
                        </div>
                    </div>
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ __('Listado de Comisiones') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive" id="tablaprro">
                                <thead>
                                    <tr>
                                        <th>Nombre del trabajador</th>
                                        <th>Fecha <th>
                                        <th>Tipo de comisiòn</th>
                                        <th>Comision</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($comisiones) > 0)
                                        @foreach ($comisiones as $res)
                                            <tr>
                                                <td>{{ $res->trabajador->name }}</td>
                                                <td>{{ $res->mes}}/{{ $res->ano  }}</td>
                                                <td>{{ $res->ano}}</td>
                                                <td>{{ $res->tipo_comision}}</td>
                                                <td>
                                                    ${{ number_format((($res->monto_comision)), 2) }}
                                                </td>
                                                <td>
                                                    <form action="{{ route('sueldo.eliminar') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $res->id }}">
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </td>
                                               
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8">No se encuentran registos de Comisiòn</td>
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

    <div class="modal" tabindex="-1" role="dialog" id="myModalFinalizar">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agergar Comisiòn</h5>
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
                            <form action="{{ route('sueldo.create') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Trabajador</label>
                                    <select name="idTrabajador" class="form-control" id="">
                                        @foreach ($trabajadores as $item)
                                            <option value="{{ $item->idUser }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Tipo Comisiòn</label>
                                    <select name="formaCobro" id="selectSala" class="form-control" onchange="cambiar()">
                                        <option value="apoyo">apoyo</option>
                                        <option value="bono 100k">bono 100k</option>
                                        <option value="bono 170k">bono 170k</option>
                                        <option value="bodas">bodas</option>
                                        <option value="Extra">Extra</option>
                                        <option value="descuento">descuento</option>
                                        <option value="impuestos">impuestos</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Monto </label>
                                    <input type="number"   class="form-control" id="valueeee"
                                        placeholder="" name="mon" required>
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



    <div class="modal" tabindex="-1" role="dialog" id="myModalFinalizar2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center">Desea reiniciar las comisiones?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="closeModalFinalizar()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <button  onclick="closeModalFinalizar2()" class="btn btn-primary">NO</button>
                        </div>
                        <div class="col">
                            <a  href="{{ route('sueldo.limpiar') }}" class="btn btn-danger">SI</a>

                        </div>
                    </div>

                     

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>
    var myModal = new bootstrap.Modal(document.getElementById('myModalFinalizar'), {
        keyboard: false
    });
    var myModal2 = new bootstrap.Modal(document.getElementById('myModalFinalizar2'), {
        keyboard: false
    });
    function openModalFinalizar(id) {
        // document.getElementById('idReservaFina').value = id;
        myModal.show();
    }

    function openModalFinalizar2() {
        myModal2.show();
    }
    function closeModalFinalizar2() {
        myModal2.hide();

    }
    function closeModalFinalizar() {
        myModal.hide();

    }
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

    function cambiar() {
        var index =document.getElementById("selectSala").selectedIndex;
        console.log(index);
        switch (index) {
            case 1:
                document.getElementById("valueeee").value= "100"
                break;
                case 2:
                document.getElementById("valueeee").value= "170"
                break;
                case 5:
                document.getElementById("valueeee").value= "-10"
                break;
                case 6:
                document.getElementById("valueeee").value= "-800"
                break;
            default:
                break;
        }
    }
    function reload() {
    window.location.href ="{!! route('sueldo.index') !!}"
}
</script>
@endsection