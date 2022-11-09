@component('templates.modal', ['modalId' => 'payroll-modal', 'title' => 'Corte de Quincena', 'close' => true,
                               'backdrop' => true])
    @slot('body')
        <form id="payroll-form">
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
        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#payroll-modal'))">Cancelar</button>
        <button type="button" id="createPayrollBtn" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
