@component('templates.modal', ['modalId' => 'events-modal', 'title' => 'Nuevo Evento', 'close' => true,
                               'backdrop' => true, 'modalSize' => 'modal-lg'])

    @slot('body')
        <form id="events-form">
            <input type="hidden" name="id" id="id" />
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="name">Cliente</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <input name="customer" id="customer" type="text" class="form-control" required />
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="eventDate">Fecha del Evento</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <input type="text" class="form-control datepicker-rome" id="eventDate" name="eventDate" readonly required />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="center">Hotel</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-building"></i>
                        </span>
                        <select name="center" id="center" class="form-control">
                            @foreach($centers as $center)
                            <option value="{{ $center->idCenter }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6" id="divHotel" >
                    <label for="hotel">Hotel Externo</label>
                    <input class="form-control" id="hotel" name="hotel" readonly />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="service">Paquete</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-heart"></i>
                        </span>
                        <select name="service" id="service" class="form-control">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6" id="divPersonalize" >
                    <label for="personalize">Paquete Personalizado</label>
                    <textarea class="form-control" id="personalize" name="personalize" rows="5" readonly></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="phone">Telefóno</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-telephone"></i>
                        </span>
                    <input type="text" name="phone" class="form-control" value="+52" id="phone" required />
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-at"></i>
                        </span>
                        <input type="email" name="email" class="form-control" id="email" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="price">Costo</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input type="text" name="price" class="form-control" id="price" onkeydown="numberOrdecimal(event)" readonly required />
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="discount">Descuento</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input type="number" name="discount" class="form-control" id="discount" required />
                    </div>
                </div>
            </div>
        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#events-modal'))">Cancelar</button>
        <button type="button" id="createEventBtn" onclick="saveEvent()" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
