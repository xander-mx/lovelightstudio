@php
  use \App\Http\Controllers\Admin\PayrollController;
@endphp

@extends('layouts.app', ['activePage' => 'payrolls.index', 'title' => 'LLS - Arqueos', 'navName' => 'Arqueo',
 'activeButton' => 'commissions'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
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
@endsection