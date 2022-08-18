@component('templates.modal', ['modalId' => 'add-payment-modal', 'title' => 'Nuevo Pago', 'close' => true,
                               'backdrop' => true ])
    @slot('body')
        <form id="events-payments-form">
            <input type="hidden" name="id_event" id="id_event" />
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="payment_type">Forma de Pago</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-credit-card-2-back"></i>
                        </span>
                        <select name="payment_type" id="payment_type" class="form-control" >
                            @foreach($paymentTypes as $paymentType)
                                <option value="{{ $paymentType }}">{{ $paymentType->message() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="paymentDate">Fecha del Pago</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <input type="text" class="form-control datepicker-rome" id="paymentDate" name="paymentDate" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="phone">Cantidad</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-cash"></i>
                        </span>
                        <input type="number" name="amount" class="form-control" id="amount" required />
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="event">Voucher</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <input name="voucher" id="voucher" type="text" class="form-control"  required />
                    </div>
                </div>
            </div>

        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#add-payment-modal'))">Cancelar</button>
        <button type="button" id="addPaymentEventBtn" class="btn btn-primary">Aceptar</button>
    @endslot

@endcomponent
