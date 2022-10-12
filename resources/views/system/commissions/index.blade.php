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
                        <div class=" row pt-3 pl-3 pr-3">
                            <label for="employee">Trabajadores</label>
                            <select name="employee" id="employee" class="form-control">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee['idUser'] }}" @if($employee['idUser']==0) selected @endif>{{ $employee['name'] }} {{ $employee['lastname'] ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-body">
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
@endsection

@push('js')
    {!! Html::script('assets/js/commissions.js') !!}
@endpush