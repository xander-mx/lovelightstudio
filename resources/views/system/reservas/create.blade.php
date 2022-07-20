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
                                    <div class="col">
                                        <div class="col-md-12 text-left">
                                            <h2>Sesión Fotográfica en {{ $centro->name }}</h2>
                                            <small>{{ $centro->description }}</small>
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="row" id="listHorarios">
                                                <div class="col">
                                                    <h5>Por la mañana</h5>
                                                    <div id="mañana"></div>
                                                </div>
                                                <div class="col">
                                                    <h5>Después del mediodía</h5>
                                                    <div id="mediodia"></div>
                                                </div>
                                                <div class="col">
                                                    <h5>Por la tarde</h5>
                                                    <div id="tarde"></div>
                                                </div>
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
    @if (request()->get('edit') != null)
    <div class="modal fade" id="modalReserva" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalHorario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> Nueva Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createReserva">
                    @csrf
                    <input type="hidden" name="idEdit" value="{{ request()->get('edit') }}">
                    <input type="hidden" name="idSchedule" id="idSchedule" value="">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="hidden" name="name" value="{{ $reserva->client->name }}">
                            <input type="text" name="" id="name" class="form-control" value="{{ $reserva->client->name }}" required disabled>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="lastname">Apellido </label>
                            <input type="hidden" name="lastname" value="{{ $reserva->client->lastname }}">
                            <input type="text" name="" id="lastname" class="form-control" value="{{ $reserva->client->lastname }}" required disabled>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pax">Pax</label>
                            <input type="hidden" name="pax" value="{{ $reserva->pax}}">
                            <input type="text" name="" id="pax" class="form-control"
                                placeholder="EJEMPLO : 3 niños 4 hombre" value="{{ $reserva->pax}}" required disabled>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pax">Numero de habitaciòn</label>
                            <input type="hidden" name="num_habitacion"  value="{{ $reserva->num_habitacion}}">
                            <input type="text" name="" id="num_habitacion"  value="{{ $reserva->num_habitacion}}" class="form-control"
                                required disabled>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="hidden" name="email"value="{{ $reserva->client->email}}" >
                            <input type="email" name="" id="email" class="form-control" value="{{ $reserva->client->email}}" required disabled> 
                        </div>
                    </div>
                   
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="cellphone">Teléfono con prefijo ej: (+1301572359)</label>
                            <input type="hidden" name="cellphone" value="{{ $reserva->client->cellphone}}" >
                            <input type="text" name="" id="cellphone" class="form-control" value="{{ $reserva->client->cellphone}}"  required
                                value="+52 "disabled>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-danger">Cancelar</button>
                <button type="button" onclick="createReserva()" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>
    @else
        <div class="modal fade" id="modalReserva" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="modalHorario" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"> Nueva Reserva</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="createReserva">
                            @csrf
                            <input type="hidden" name="idSchedule" id="idSchedule" value="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="lastname">Apellido </label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pax">Pax</label>
                                    <input type="text" name="pax" id="pax" class="form-control"
                                        placeholder="EJEMPLO : 3 niños 4 hombre" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pax">Numero de habitaciòn</label>
                                    <input type="text" name="num_habitacion" id="num_habitacion" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="cellphone">Teléfono con prefijo ej: (+1301572359)</label>
                                    <input type="text" name="cellphone" id="cellphone" class="form-control" required
                                        value="+52 ">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" class="btn btn-danger">Cancelar</button>
                        <button type="button" onclick="createReserva()" class="btn btn-primary">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
    @if ($specific)
        <script>
            var fechaSelected = "";
            var horarios = []

            function selectDay(day) {
                var UrlPath_ = UrlHost_ + "/administrator/ajax/horario/day";
                $.ajax({
                    type: 'GET',
                    url: UrlPath_,
                    data: {
                        day: day,
                        idCenter: {{ $ids }}
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $(".content_loading").fadeIn("slow");
                    },
                    success: function(response) {
                        console.log(response)
                        horarios = response.data
                        $(".content_loading").fadeOut("slow");
                        showHorarios();
                        fechaSelected = day;
                    },
                    error: function(xhr) {
                        //   console.log("ERROR");
                        $(".content_loading").fadeOut("slow");
                        swal('Ops!', 'Hubo un error, por favor intentelo más tarde', 'error')
                    }
                });
            }

            function showHorarios() {
                $('#mañana').empty()
                $('#mediodia').empty()
                $('#tarde').empty()
                horarios.forEach((value) => {
                    if (value.hour < 12) {
                        $('#mañana').append(`
                            <a href="Javascript:;" onclick="openModalReserva(${value.idSchedule})" class="btn btn-primary active"> ${value.hour}:${value.minute}</a>
                        `)
                    } else if (value.hour > 12 && value.hour < 18) {
                        $('#mediodia').append(`
                            <a href="Javascript:;" onclick="openModalReserva(${value.idSchedule})" class="btn btn-primary active"> ${value.hour}:${value.minute}</a>
                        `)
                    } else {
                        $('#tarde').append(`
                            <a href="Javascript:;" onclick="openModalReserva(${value.idSchedule})" class="btn btn-primary active"> ${value.hour}:${value.minute}</a>
                        `)
                    }
                });

            }

            function openModalReserva(id) {
                if (id != null && fechaSelected != "") {
                    $('#idSchedule').val(id);
                    $('#modalReserva').modal()
                } else {
                    if (id == null) {
                        swal('Ops!', 'Por favor seleccione un horario', 'error')
                    } else {
                        swal('Ops!', 'Por favor seleccione una fecha', 'error')
                    }
                }
            }

            function createReserva() {
                var formData = new FormData(document.getElementById("createReserva"));
                formData.append("date", fechaSelected);
                formData.append("idCenter", "{{ $centro->idCenter }}");

                $.ajax({
                    url: UrlHost_ + "/administrator/reserva/store",
                    data: formData,
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $(".content_loading").fadeIn("slow");
                    },
                    success: function(response) {
                        console.log(response)
                        $(".content_loading").fadeOut("slow");
                        if (response.code == 200) {
                            swal('Muy bien!', 'La reserva se ha realizado correctamente', 'success');
                            setTimeout(() => {
                                window.location = '{{ route('reserva.index') }}'
                            }, 3000);
                        } else if (response.code == 400) {
                            var datos = response.data
                            var message = ""
                            Object.entries(datos).forEach(([key, value]) => {
                                value.forEach((error) => {
                                    message += "- " + error + "\n";
                                })
                            });
                            swal(response.message, message, 'error')
                        } else {
                            swal('Ops!', response.message, 'error')
                        }
                    },
                    error: function(xhr) {
                        $(".content_loading").fadeOut("slow");

                        swal({
                            title: 'Ups!',
                            // text: 'Hemos tenido problemas al enviar (ERR), intenta mas tarde',
                            text: 'campos vacio',
                            type: "warning",
                            animation: false,
                            showConfirmButton: true
                        });
                    }
                });
            }
        </script>
    @endif
    <script src="{{ asset('assets/js/calendar.js') }}"></script>

@endpush
