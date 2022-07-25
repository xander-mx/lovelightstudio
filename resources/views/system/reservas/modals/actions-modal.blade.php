@component('templates.modal', ['modalId' => 'actions-modal', 'title' => 'Acciones', 'clickClose' => 'closeAction()',
           'close' => true, 'backdrop' => true])
    @slot('body')
        <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <form action="{{ route('reserva.changeState') }}" method="post">
                        @csrf
                        <input type="hidden" name="idReserva" id="reservaCan_2">
                        <input type="hidden" name="state" value="2">
                        <button class="btn btn-danger">Cancelar Reservación</button>
                    </form>
                </div>
                <div class="col-6">
                    <a class="btn btn-info" href=""  id="linssk">Reagendar Reservación</a>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
