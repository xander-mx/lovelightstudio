@php
  use \App\Http\Controllers\Admin\CommissionsController;
@endphp
@extends('layouts.app', ['activePage' => 'commissions.index', 'title' => 'LLS - Comisiones', 'navName' => 'Comisiones',
 'activeButton' => 'commissions'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <div class="row">
                    <div class="card col-12">
                        <div class="float-left row pt-3 pl-3">
                            <label for="status">Trabajadores</label>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 ">
                                    <select name="employee" id="employee" class="form-control">
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee['idUser'] }}" @if($employee['idUser']==0) selected @endif>{{ $employee['name'] }} {{ $employee['lastname'] ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="display: inline-block" class="form-group col-sm-12 col-md-6 col-lg-6 ">
                                    <button type="button" style="display: inline-block" class="btn btn-primary float-right" id="commission-add">
                                        <i class="bi bi-plus"></i>Nueva Comisión
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card col-12">
                        <div class="card-body">
                            @include('templates.datatable', [
                                  'tableId' => 'commissions-table',
                                  'title' => 'Comisiones',
                                  'serverSide' => 'true',
                                  'headers' => CommissionsController::$headers,
                                  'fields' => CommissionsController::$fields,
                                  'urlTable' => route(CommissionsController::$routeTable),
                                  'dataFunction' => 'getEmployee_fn'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('system.commissions.modals.commissions-modal')
@endsection

@push('js')
    {!! Html::script('assets/js/commissions.js') !!}
@endpush