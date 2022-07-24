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
                                @foreach ($centros as $key => $centro)
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="card col-md-8 pl-5">
                                            <div class="card-header">
                                                <div class="row align-items-center ">
                                                    <div class="col-md-8">
                                                        <h3 class="mb-0">
                                                            {{ __('Sesión Fotográfica en ') . $centro->name }}
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
                                        <div class="col-md-2"></div>
                                    </div>
                                @endforeach
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

                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <h2>Reservaciones</h2>
                                            </div>
                                            <div class="col-12">
                                                <table id="reservation-table" class="table table-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th>Horario</th>
                                                        <th>Recepcionista</th>
                                                        <th>Cliente</th>
                                                        <th>Paquete</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        @component('templates.modal', ['modalId' => 'modalReserva', 'title' => 'Nueva Reserva', 'backdrop' => true])
            @slot('body')
                <form id="reservation-form">
                    @csrf
                    @if (request()->get('edit') != null)
                        <input type="hidden" name="idEdit" value="{{ request()->get('edit') }}">
                    @endif
                    <input type="hidden" name="idSchedule" id="idSchedule" value="">
                    <input type="hidden" name="idCenter" id="idCenter" value="{{$centro->idCenter}}">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label for="name">Nombre y Apellido</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="@isset($reserva){{ $reserva->client->name }}@endisset" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-12">
                            <label for="pax">Pax</label>
                            <input type="text" name="pax" id="pax" class="form-control"
                                   value="@isset($reserva){{ $reserva->pax}}@endisset" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-12">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="@isset($reserva){{ $reserva->client->email}}@endisset" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6, col-lg-6">
                            <label for="cellphone">Teléfono</label>
                            <input type="text" name="cellphone" id="cellphone" class="form-control"
                                   value="+52 @isset($reserva){{ $reserva->client->cellphone}}@endisset" required>
                        </div>
                        <div class="form-group col-sm-12 col-md-6, col-lg-6">
                            <label for="pax">Número de Habitación</label>
                            <input type="text" name="num_habitacion" id="num_habitacion"
                                   value="@isset($reserva){{ $reserva->num_habitacion}}@endisset" class="form-control" required>
                        </div>
                    </div>
                </form>
            @endslot
            @slot('footer')
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" id="createReservationBtn" onclick="createReserva()" class="btn btn-primary">Aceptar</button>
            @endslot
        @endcomponent
@endsection

@push('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/calendar.js') }}"></script>
    {!! Html::script('assets/js/reservations.js') !!}
    @if ($specific)
        <script>
            let fechaSelected = "";
            let routeReservaIndex = '{{ route('reserva.index') }}';
            let routeReservaStore = '{{ route('reserva.store') }}';
        </script>
    @endif

@endpush
