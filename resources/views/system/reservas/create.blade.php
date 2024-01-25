@php
  use \App\Http\Controllers\Admin\ReservaController;
@endphp

@extends('layouts.app', ['activePage' => 'reservas.create', 'title' => 'LLS - Nueva Reserva', 'navName' => 'Nueva
reserva', 'activeButton' => 'reservas'])

@push('styles')
    @if ($specific)
        <link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" />
    @endif
@endpush

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
                                <p> {{ trans('Atención') }} </p>
                                <ul>
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ __('Agendar nueva reserva') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            @if (!$specific)
                                <div class="row">
                                @foreach ($centros as $key => $centro)
                                        <div class="col-sm-12 col-md-6 col-lg-6 ">
                                            <div class="card ">
                                                <div class="card-header">
                                                    <div class="row align-items-center ">
                                                        <div class="col-md-8">
                                                            <h3 class="mb-0">
                                                                {{  $centro->name }}
                                                            </h3>
                                                        </div>
                                                        <div class="col text-right align-items-center">
                                                            <a href="{{ route('reserva.create', ['id' => $centro->idCenter]) }}"
                                                                class="btn btn-primary"> Reservar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <small>
                                                        {{ $centro->description }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="calendar"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="col-md-12 text-left">
                                            <h2>Sesión Fotográfica en {{ $centro->name }}</h2>
                                            <small>{{ $centro->description }}</small>
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="row" id="listHorarios">
                                                <div class="col-4">
                                                    <h5>Por la mañana</h5>
                                                    <div id="mañana"></div>
                                                </div>
                                                <div class="col-4">
                                                    <h5>Después del mediodía</h5>
                                                    <div id="mediodia"></div>
                                                </div>
                                                <div class="col-4">
                                                    <h5>Por la tarde</h5>
                                                    <div id="tarde"></div>
                                                </div>
                                            </div>
                                        </div>

                                        @include('templates.datatable', [ 'tableId' => 'reservation-table',
                                          'title' => 'Reservaciones',
                                          'serverSide' => 'false',
                                          'headers' => ReservaController::$headers,
                                          'fields' => ReservaController::$fields])
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('system.reservas.modals.reservation-modal')
    </div>

@endsection

@push('js')

    @if ($specific)
        {!! Html::script('assets/js/calendar.js') !!}
        {!! Html::script('assets/js/reservations.js') !!}
        <script>
            let fechaSelected = "";
            let routeReservaIndex = '{{ route('reserva.index') }}';
            let routeReservaStore = '{{ route('reserva.store') }}';
            let routeReservaSchedule = '{{ route('ajax.horarios') }}';
        </script>
    @endif
@endpush
