@component('templates.modal', ['modalId' => 'myModalFinalizar', 'title' => 'Formato  finalización de Reserva',
           'clickClose' => 'closeModalFinalizar()', 'close' => true])
    @slot('body')
        <div class="row">
            <div class="col">
                <strong>Por favor ingrese los campos para poder enviar los datos del cliente</strong>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <form action="{{ route('reserva.finalizar') }}" method="post">
                    @csrf
                    <input type="hidden" name="idReserva" id="idReservaFina">
                    <div class="mb-3">
                        <label for="montoCobrado" class="form-label">Monto cobrado</label>
                        <input type="number" min="0" class="form-control" id="montoCobrado" placeholder="" name="montoCobrado" required>
                    </div>

                    <div class="mb-3">
                        <label for="formaCobro" class="form-label">Forma de cobro</label>
                        <select name="formaCobro" id="formaCobro" class="form-control">
                            <option value="Tarjeta" selected>Tarjeta</option>
                            <option value="Efectivo" >Efectivo</option>
                            <option value="Deposito" >Deposito</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="micelaneo" class="form-label">N° Miscelaneo</label>
                        <input type="text" class="form-control" id="micelaneo" placeholder="" name="micelaneo" required>
                    </div>

                    <div class="mb-3">
                        <label for="recibo" class="form-label">Recibo</label>
                        <input type="text" class="form-control" id="recibo" placeholder="" name="recibo" required>
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" min="0" class="form-control" id="link" placeholder="" name="link" required>
                    </div>

                    <div class="mb-3">
                        <label for="pwsd" class="form-label">Password</label>
                        <input type="text" class="form-control" id="pwsd" placeholder="" name="pwsd" required>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    @endslot
@endcomponent