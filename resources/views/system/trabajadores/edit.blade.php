@extends('layouts.app', ['activePage' => 'trabajador', 'title' => 'LLS - Nuevo trabajador', 'navName' => 'Trabajadores', 'activeButton' => 'trabajador'])

@section('content')

    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
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
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ __('Crear Trabajador') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <form action="{{route('trabajador.puti')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nombre</label>
                                                <input type="hidden" name="id" value="{{ $usuario->idUser }}">
                                                <input class="form-control" type="text" name="nombre" id="name" required placeholder="Digite nombre" value="{{ $usuario->name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastname">Apellido</label>
                                                <input class="form-control" type="text" name="apellido" id="lastname" required placeholder="Digite apellido" value="{{ $usuario->lastname }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email">Correo</label>
                                                <input class="form-control" type="email" name="email" id="email" required placeholder="Digite un correo" value="{{ $usuario->email }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Numero de seguro social</label>
                                                <input class="form-control" type="text"  name="seguro_social"  required placeholder="Numero de seguro social" disabled value="{{ $usuario->seguro_social }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="confirmation_password">Curp</label>
                                                <input class="form-control" type="text"  name="curp"   required placeholder="Curp" value="{{ $usuario->curp }}" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Contraseña Nueva</label>
                                                <input class="form-control" type="password" min="1" maxlength="15" name="pwsd" id="password" required placeholder="Digite la contraseña">
                                            </div>
                                        </div>
                                      

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary">Añadir</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
