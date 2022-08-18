@php
  use App\Http\Controllers\Admin\PaymentsController;
@endphp


@extends('layouts.app', ['activePage' => 'weddings.payments.index', 'title' => 'LLS - Pagos', 'navName' => 'Listado
de Pagos', 'activeButton' => 'weddings'])

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
                                <button type="button" class="btn btn-primary float-right" id="payments-add">
                                    <i class="bi bi-plus"></i>Nuevo Pago
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card col-12">
                            <div class="card-body">
                                @include('templates.datatable', [
                                    'tableId' => 'payments-table',
                                    'title' => 'Pagos',
                                    'serverSide' => 'true',
                                    'headers' => PaymentsController::$headers,
                                    'fields' => PaymentsController::$fields,
                                    'urlTable' => route(PaymentsController::$routeIndexTable)
                                ])
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @include('system.weddings.payments.modals.payments-modal')
@endsection

@push('js')
    <script>
        let routeEventAucomplete = '{{ route('weddings.events.autocomplete',[':search']) }}'
        let routePaymentSave = '{{ route('weddings.payments.save') }}'
        let routePaymentGetInformation = '{{ route('weddings.payments.information',[':id']) }}'
        let routePaymentDelete = '{{ route('weddings.payments.delete', [':id']) }}'
    </script>
    {!! Html::script('assets/js/payments.js') !!}
@endpush