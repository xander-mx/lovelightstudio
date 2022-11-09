@php
  use \App\Http\Controllers\Admin\PayrollController;
@endphp

@extends('layouts.app', ['activePage' => 'payrolls.index', 'title' => 'LLS - Arqueos', 'navName' => 'Arqueo',
 'activeButton' => 'commissions'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <div class="row justify-content-center">
                    <div class="card col-lg-3 col-md-12 col-sm-12 text-white bg-success ml-2 mr-2">
                        <div class="card-header text-center font-weight-bold">Total Arqueo</div>
                        <div class="card-body">
                            <p class="card-text text-center font-weight-bold">{{$total}}</p>
                        </div>
                    </div>
                    <div class="card col-lg-3 col-md-12 col-sm-12 text-white bg-warning ml-2 mr-2">
                        <div class="card-header text-center font-weight-bold">Total Sesiones</div>
                        <div class="card-body">
                            <p class="card-text text-center font-weight-bold">{{$reservations}}</p>
                        </div>
                    </div>
                    <div class="card col-lg-3 col-md-12 col-sm-12 text-white bg-primary ml-2 mr-2">
                        <div class="card-header text-center font-weight-bold">Total Otros</div>
                        <div class="card-body">
                            <p class="card-text text-center font-weight-bold">{{$others}}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="button" class="btn btn-primary float-right" id="payroll-add">
                        <i class="bi bi-plus"></i>Crear Arqueo
                    </button>
                    <div class="card col-12">
                        <div class="card-body">
                            @include('templates.datatable', [
                                  'tableId' => 'nextpayroll-table',
                                  'title' => 'Siguiente Arqueo',
                                  'serverSide' => 'true',
                                  'headers' => PayrollController::$headersNext,
                                  'fields' => PayrollController::$fieldsNext,
                                  'urlTable' => route(PayrollController::$routeNextTable)])
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card col-12">
                        <div class="card-body">
                            @include('templates.datatable', [
                                  'tableId' => 'payrolls-table',
                                  'title' => 'Arqueos',
                                  'serverSide' => 'true',
                                  'headers' => PayrollController::$headers,
                                  'fields' => PayrollController::$fields,
                                  'urlTable' => route(PayrollController::$routeTable)])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('system.payroll.modals.payroll-modal')
@endsection

@push('js')
    <script>
        let routeCreatePayroll = '{{ route('web.payrolls.create') }}'
        let routeRecalculate = '{{ route('web.payrolls.recalculate', [':id']) }}'
        let routeStatusPayroll = '{{ route('web.payrolls.status', [':id', ':status']) }}'
    </script>
    {!! Html::script('assets/js/payrolls.js') !!}
@endpush