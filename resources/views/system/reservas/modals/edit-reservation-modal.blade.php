@component('templates.modal', ['modalId' => 'editReservationModal', 'title' => 'Editar Reserva', 'backdrop' => true,
           'close' => true, 'clickClose' => 'closeModalReserva()'])
    @slot('body')
        <form id="edit-reservation-form">
            @csrf
            <input type="hidden" name="id" />
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="name">Nombre y Apellido</label>
                    <input type="text" name="customer" id="customer" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-12">
                    <label for="pax">Pax</label>
                    <input type="text" name="pax" id="pax" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-12">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-6, col-lg-6">
                    <label for="cellphone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control"  required>
                </div>
                <div class="form-group col-sm-12 col-md-6, col-lg-6">
                    <label for="pax">Número de Habitación</label>
                    <input type="text" name="num_habitacion" id="num_habitacion" class="form-control" required>
                </div>
            </div>
        </form>
    @endslot
    @slot('footer')
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" id="editReservationBtn" onclick="editReserve()" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
