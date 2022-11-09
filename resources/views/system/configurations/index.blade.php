@php
use \App\Http\Controllers\Admin\ConfigurationsController;
@endphp

@extends('layouts.app', ['activePage' => 'configurations.index', 'title' => 'LLS - Configuraciones', 'navName' => 'Configuraciones',
 'activeButton' => 'configurations'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <div class="row">
                    <div class="card col-12">
                        <div class="card-body">
                            @include('templates.datatable', [
                                  'tableId' => 'configurations-table',
                                  'title' => 'Configuraciones',
                                  'serverSide' => 'true',
                                  'headers' => ConfigurationsController::$headers,
                                  'fields' => ConfigurationsController::$fields,
                                  'urlTable' => route(ConfigurationsController::$routeTable)])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('system.configurations.modals.configuration-modal')
@endsection

@push('js')
    <script>
        let routeGetInformation = '{{ route('web.configurations.information', [':id']) }}'
        let routeUpdate = '{{ route('web.configurations.update') }}'
    </script>
    {!! Html::script('assets/js/configurations.js') !!}
@endpush