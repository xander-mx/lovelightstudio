@php
    use App\Http\Controllers\Admin\EventsController;
@endphp

@extends('layouts.app', ['activePage' => 'weddings.events.index', 'title' => 'LLS - Eventos', 'navName' => 'Listado
de Eventos', 'activeButton' => 'weddings'])

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
                                <div style="display: inline-block" class="form-group col-sm-12 col-md-6 col-lg-6 float-left">
                                    <label for="status">Estatus</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-filter-circle"></i>
                                        </span>
                                        <select name="status" id="status" class="form-control">
                                            @foreach($status as $s)
                                                <option value="{{ $s }}" @if($s == 'PENDIENTE') selected @endif>{{ $s->message() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div style="display: inline-block" class="form-group col-sm-12 col-md-6 col-lg-6 float-left">
                                    <br/>
                                    <button type="button" style="display: inline-block" class="btn btn-primary float-right" id="events-add">
                                        <i class="bi bi-plus"></i>Nuevo Evento
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-12">
                            <div class="card-body">
                                @include('templates.datatable', [
                                    'tableId' => 'events-table',
                                    'title' => 'Eventos',
                                    'serverSide' => 'true',
                                    'headers' => EventsController::$headers,
                                    'fields' => EventsController::$fields,
                                    'urlTable' => route(EventsController::$routeIndexTable),
                                    'columnProp' => [['prop' => 'width', 'propValue' => 250,'target' => '2,3']],
                                    'dataFunction' => 'getStatus_fn'
                                ])
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @include('system.weddings.events.modals.events-modal')
    @include('system.weddings.events.modals.add-payment-modal')
    @include('system.weddings.events.modals.info-payments-modal')
@endsection

@push('js')
    <script>
        let routeGetDataService = '{{ route('weddings.services.information', [':id']) }}';
        let routeSaveEvent = '{{ route('weddings.events.save') }}';
        let routeGetEvent = '{{ route('weddings.events.information',[':id']) }}';
        let routeDeleteEvent = '{{ route('weddings.events.delete', [':id']) }}';
        let routeAddPaymentSave = '{{ route('weddings.payments.save') }}';
        let externalHotel = {{$external}};
        let service_personalize = {{ $service_personalize }};
        let routeInfoPayments = '{{ route('weddings.events.payments.table',[':id']) }}';
        let routeInfoPaymentsRender;
    </script>
    {!! Html::script('assets/js/events.js') !!}
@endpush