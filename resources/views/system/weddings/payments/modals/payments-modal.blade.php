@component('templates.modal', ['modalId' => 'payments-modal', 'title' => 'Nuevo Pago', 'close' => true,
                               'backdrop' => true ])
    @slot('body')
        <form id="payments-form">
            <input type="hidden" name="id" id="id" />
            <input name="id_event" id="id_event" type="text" class="form-control" hidden />
            <div class="row">
                <div class="form-group col-12 ui-front">
                    <label for="event">Evento</label>
                    <div class="input-group  ui-widget">
                        <span class="input-group-text">
                            <i class="bi bi-box2-heart"></i>
                        </span>
                        <input name="event" id="event" type="text" class="form-control" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="service">Forma de Pago</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-credit-card-2-back"></i>
                        </span>
                        <select name="payment_type" id="payment_type" class="form-control" disabled>
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
                        <input type="text" class="form-control datepicker-rome" id="paymentDate" name="paymentDate" readonly required />
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
                        <input type="number" name="amount" class="form-control" id="amount" readonly required />
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="event">Voucher</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <input name="voucher" id="voucher" type="text" class="form-control" readonly required />
                    </div>
                </div>
            </div>

        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#payments-modal'))">Cancelar</button>
        <button type="button" id="createPaymentBtn" class="btn btn-primary">Aceptar</button>
    @endslot

@endcomponent
