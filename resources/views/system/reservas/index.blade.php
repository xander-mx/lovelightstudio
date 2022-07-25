@php
  use \App\Http\Controllers\Admin\ReservaController;
@endphp

@extends('layouts.app', ['activePage' => 'reservas.index', 'title' => 'LLS - Listado Reserva', 'navName' => 'Listado
Reserva', 'activeButton' => 'reservas'])

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
                <div class="row">
                    <div class="card col-md-12">
                        <div class="card-body">
                            @if (Auth::user()->idPerfil <= 2 ||Auth::user()->idPerfil == 8 )
                                @include('templates.datatable', [
                                  'tableId' => 'reservations-table',
                                  'title' => 'Listado de Reservas',
                                  'serverSide' => 'true',
                                  'headers' => ReservaController::$indexHeaders,
                                  'fields' => ReservaController::$indexFields,
                                  'urlTable' => route(ReservaController::$routeIndexTable)])
                            @else
                                @switch(Auth::user()->idPerfil)
                                    @case(3)
                                        @include('templates.datatable', [
                                          'tableId' => 'reservations-table',
                                          'title' => 'Listado de Reservas',
                                          'serverSide' => 'true',
                                          'headers' => ReservaController::$indexHeadersPhotographer,
                                          'fields' => ReservaController::$indexFieldsPhotographer,
                                          'urlTable' => route(ReservaController::$routeIndexTable)])
                                        @break
                                    @case(4)
                                        @include('templates.datatable', [
                                          'tableId' => 'reservations-table',
                                          'title' => 'Listado de Reservas',
                                          'serverSide' => 'true',
                                          'headers' => ReservaController::$indexHeadersClient,
                                          'fields' => ReservaController::$indexFieldsClient,
                                          'urlTable' => route(ReservaController::$routeIndexTable)])
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
        @include('system.reservas.modals.finalize-modal')
    @endif

    {{-- Dialog aignar usuario --}}
    @if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 || Auth::user()->idPerfil == 8 || Auth::user()->idPerfil == 3 || Auth::user()->idPerfil == 4)
        @include('system.reservas.modals.actions-modal')
        @include('system.reservas.modals.assign-photographer-modal')
    @endif
@endsection

@push('js')
    {!! Html::script('assets/js/reservationsIndex.js') !!}
    <script>
        let foto =JSON.parse( '{!! $trabajadores !!}');
        let routeEditReservation = '{{ route('reserva.edit', [':center',':id']) }}';
    </script>
@endpush