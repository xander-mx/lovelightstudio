@component('templates.modal', ['modalId' => 'configuration-modal', 'title' => 'Editar Configuración', 'close' => true,
                               'backdrop' => true ])
    @slot('body')
        <form id="configuration-form">
            <input type="hidden" name="id" id="id" />
            <div class="row">
                <div class="form-group col-12">
                    <label for="event">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-wrench"></i>
                        </span>
                        <input name="name" id="name" type="text" class="form-control" readonly />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12">
                    <label for="event">Valor</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-pen"></i>
                        </span>
                        <input name="value" id="value" type="text" class="form-control" />
                    </div>
                </div>
            </div>
        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#configuration-modal'))">Cancelar</button>
        <button type="button" id="editConfigurationBtn" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
