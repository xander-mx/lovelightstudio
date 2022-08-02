@php
  use App\Http\Controllers\Admin\ServicesController;
@endphp

@extends('layouts.app', ['activePage' => 'weddings.services.index', 'title' => 'LLS - Paquetes Fotográficos', 'navName' => 'Listado
de Paquetes Fotográficos', 'activeButton' => 'weddings'])

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
                        <div class="card col-md-12">
                            <div class="card-body">
                                <button type="button" class="btn btn-primary float-right" id="services-add">
                                    <i class="bi bi-plus"></i>Nuevo Paquete
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-12">
                            <div class="card-body">
                                @include('templates.datatable', [
                                    'tableId' => 'services-table',
                                    'title' => 'Paquetes Fotográficos',
                                    'serverSide' => 'true',
                                    'headers' => ServicesController::$headers,
                                    'fields' => ServicesController::$fields,
                                    'urlTable' => route(ServicesController::$routeIndexTable)
                                ])
                            </div>
                        </div>
                    </div>
                @include('system.weddings.services.modals.services-modal')
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let routeSaveServices = '{{ route('weddings.services.save') }}';
        let routeGetService = '{{ route('weddings.services.information', [':id']) }}';
        let routeDeleteService = '{{ route('weddings.services.delete', [':id']) }}';
    </script>
    {!! Html::script('assets/js/services.js') !!}
@endpush