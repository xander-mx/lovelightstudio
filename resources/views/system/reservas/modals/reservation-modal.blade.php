@component('templates.modal', ['modalId' => 'modalReserva', 'title' => 'Nueva Reserva', 'backdrop' => true,
           'close' => true, 'clickClose' => 'closeModalReserva()'])
    @slot('body')
        <form id="reservation-form">
            @csrf
            <input type="hidden" name="idEdit" value="@isset($reserva){{ $reserva->idReservation  }} @endisset">
            <input type="hidden" name="idSchedule" id="idSchedule" value="">
            <input type="hidden" name="idCenter" id="idCenter" value="{{$centro->idCenter}}">
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="name">Nombre y Apellido</label>
                    <input type="text" name="customer" id="customer" class="form-control"
                           value="@isset($reserva){{ $reserva->customer }}@endisset" required>
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
                           value="@isset($reserva){{ $reserva->email}}@endisset" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-6, col-lg-6">
                    <label for="cellphone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                           value="+52 @isset($reserva){{$reserva->phone}}@endisset" required>
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
