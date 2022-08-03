@php
  use App\Http\Controllers\Admin\ReservesSoldController;
@endphp
@extends('layouts.app', ['activePage' => 'reservas', 'title' => 'LLS - Listado Reserva', 'navName' => 'Sesiones Vendidas',
 'activeButton' => 'reserves.sold.index'])

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
            </div>

            <div class="row">
                <div class="col text-left">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('sold-table', 'ventas')">
                        <i class="bi bi-file-earmark-excel"></i>  Exportar a excel</button>
                </div>
                <div class="card col-md-12">
                    <div class="card-body">
                        @if (Auth::user()->idPerfil <= 2 || Auth::user()->idPerfil == 8)
                            @include('templates.datatable', [
                              'tableId' => 'sold-table',
                              'title' => 'Listado de Sesiones Vendidas',
                              'serverSide' => 'true',
                              'responsive' => false,
                              'headers' => ReservesSoldController::$headers,
                              'fields' => ReservesSoldController::$fields,
                              'urlTable' => route(ReservesSoldController::$routeIndex),
                              'columnProp' => [['prop' => 'width', 'propValue' => 70,'target' => '0,9,10,11,12,13']]
                            ])
                        @endif

                        @if (Auth::user()->idPerfil > 2 && Auth::user()->idPerfil != 8)
                                @include('templates.datatable', [
                                  'tableId' => 'sold-table',
                                  'title' => 'Listado de Sesiones Vendidas',
                                  'serverSide' => 'true',
                                  'headers' => ReservesSoldController::$headersPhotographer,
                                  'fields' => ReservesSoldController::$fieldsPhotographer,
                                  'urlTable' => route(ReservesSoldController::$routeIndex)
                                ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {!! Html::script('assets/js/reservessold.js') !!}
@endpush