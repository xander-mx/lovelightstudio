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
                <div class="card col-12" style="display: inline">
                    <div class="float-left row pt-2 pl-2 pb-1">
                        <div class="form-group col-sm-12 col-md-6 col-lg-6">
                            <label></label>
                            <button type="button" class="btn btn-success" onclick="exportTableToExcel('sold-table', 'ventas')">
                                <i class="bi bi-file-earmark-excel"></i>  Exportar a excel</button>
                        </div>
                    </div>
                    <div class="float-right row pt-2 pl-2 pb-1 col-8">
                        <div class="form-group col-sm-12 col-md-4 col-lg-4">
                            <label for="initDate">Fecha Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                                <input type="text" class="form-control datepicker-rome" id="initDate" name="initDate" readonly required />
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4">
                            <label for="endDate">Fecha Final</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                                <input type="text" class="form-control datepicker-rome" id="endDate" name="endDate" readonly required />
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-4">
                            <label></label>
                            <div class="input-group pt-1">
                            <button type="button" class="btn btn-esmerald" id="searchSolds">
                                <i class="bi bi-search-heart"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-body">
                        @if (Auth::user()->idPerfil <= 2 || Auth::user()->idPerfil == 8 && Auth::user()->idPerfil != 9)
                            @include('templates.datatable', [
                              'tableId' => 'sold-table',
                              'title' => 'Listado de Sesiones Vendidas',
                              'serverSide' => 'true',
                              'scrollX' => true,
                              'headers' => ReservesSoldController::$headers,
                              'fields' => ReservesSoldController::$fields,
                              'urlTable' => route(ReservesSoldController::$routeIndex),
                              'dataFunction' => 'filter_dates_fn',
                              'columnProp' => [['prop' => 'width', 'propValue' => 70,'target' => '0,9,10,11,12,13']]
                            ])
                        @endif

                        @if (Auth::user()->idPerfil > 2 && Auth::user()->idPerfil != 8 && Auth::user()->idPerfil != 9)
                                @include('templates.datatable', [
                                  'tableId' => 'sold-table',
                                  'title' => 'Listado de Reservas Vendidas',
                                  'serverSide' => 'true',
                                  'headers' => ReservesSoldController::$headersPhotographer,
                                  'fields' => ReservesSoldController::$fieldsPhotographer,
                                  'urlTable' => route(ReservesSoldController::$routeIndex),
                                  'dataFunction' => 'filter_dates_fn'
                                ])
                        @endif
                            @if (Auth::user()->idPerfil == 9)
                                @include('templates.datatable', [
                                  'tableId' => 'sold-table',
                                  'title' => 'Listado de Reservas Vendidas',
                                  'serverSide' => 'true',
                                  'headers' => ReservesSoldController::$headersReader,
                                  'fields' => ReservesSoldController::$fieldsReader,
                                  'urlTable' => route(ReservesSoldController::$routeIndex),
                                  'dataFunction' => 'filter_dates_fn'
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