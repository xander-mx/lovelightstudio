@extends('layouts.app', ['activePage' => 'trabajador', 'title' => 'LLS - Trabajadores', 'navName' => 'Trabajadores', 'activeButton' => 'trabajador'])
@section('stylebb')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="section-image">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="row">
                @if(Session::has('message'))
                    <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            😉 {{Session::get('message')}} 😉
                        </div>
                    </div>
                    </div>
                @endif
                @if(Session::has('message_error'))
                    <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            😓 {{Session::get('message_error')}} 😒
                        </div>
                    </div>
                    </div>
                @endif
                @if(count($errors) > 0)
                    <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p> {{  trans('restaurantes.corregirErrores') }} </p>
                            <ul>
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    </div>
                @endif
                <div class="card col-md-12">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-0">{{ __('Listado de Trabajadores') }}</h3>
                            </div>
                            <div class="col">
                                <div class="text-right">
                                    <a href="{{route('trabajador.create')}}" type="button" rel="tooltip" title="" class="btn btn-info" data-original-title="Crear trabajador">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th>Nº Reserva activo</th>
                                    <th>Estado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($trabajadores) > 0)
                                    @foreach ($trabajadores as $trabajador)
                                        <tr>
                                            <td>
                                                {{$trabajador->name}}
                                            </td>
                                            <td>
                                                {{$trabajador->lastname}}
                                            </td>
                                            <td>
                                                {{ $trabajador->email }}
                                            </td>
                                            <td>
                                                {{$trabajador->getPerfil->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $reservasNumeroHoy[$trabajador->idUser] }}
                                            </td>
                                            <td>
                                                @switch($trabajador->state)
                                                    @case(0)
                                                    <form action="{{ route('trabajador.change') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $trabajador->idUser }}">
                                                        <input type="hidden" name="state" value="1">
                                                        <button type="submit" class="btn btn-warning">
                                                            <span>Inactivo</span>
                                                        </button>
                                                    </form>
                                                 
                                                        @break
                                                    @case(1)
                                                    <form action="{{ route('trabajador.change') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $trabajador->idUser }}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button type="submit" class="btn btn-success">
                                                            <span>Activo</span>
                                                        </button>
                                                    </form>
                                                        
                                                        @break
                                                    @default
                                                        <span class="badge badge-danger">
                                                            Eliminado
                                                        </span>
                                                @endswitch
                                              
                                            </td>
                                            <td>  <form action="{{ route('trabajador.change') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $trabajador->idUser }}">
                                                <input type="hidden" name="state" value="3">
                                                <button type="submit" class="btn btn-danger">
                                                    Eliminar
                                                    
                                                </button>
                                            </form>
                                            <a href="{{ route('trabajador.edit.view') }}?idUser={{ $trabajador->idUser }}" class="btn btn-info">Editar</a>
                                        
                                        </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">No hay registros de trabajadores</td>
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
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    
@endsection