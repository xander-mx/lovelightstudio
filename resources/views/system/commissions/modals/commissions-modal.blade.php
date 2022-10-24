@component('templates.modal', ['modalId' => 'commissions-modal', 'title' => 'Nueva Comisión', 'close' => true,
                               'backdrop' => true])
    @slot('body')
        <form id="events-form">
            <input type="hidden" name="id" id="id" />
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="center">Trabajador</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <select name="photographer" id="photographer" class="form-control">
                            @foreach($employees as $employee)
                                @if($employee['idUser']!=0)
                                    <option value="{{ $employee['idUser'] }}">{{ $employee['name'] }} {{ $employee['lastname'] ?? '' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="name">Concepto</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-card-text"></i>
                        </span>
                        <input name="concept" id="concept" type="text" class="form-control" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="name">Monto</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input name="amount" id="amount" type="number" class="form-control" required />
                    </div>
                </div>
            </div>
        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#commissions-modal'))">Cancelar</button>
        <button type="button" id="createCommissionBtn" onclick="saveEvent()" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
