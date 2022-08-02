@component('templates.modal', ['modalId' => 'services-modal', 'title' => 'Nuevo Paquete',
                        'close' => true, 'backdrop' => true])
    @slot('body')
        <form id="services-form">
            <input type="hidden" name="id" id="id" />
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="name">Nombre Paquete</label>
                    <input name="name" id="name" type="text" class="form-control"
                           value="@isset($service){{ $service->name }}@endisset" required />
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="name">Descripción</label>
                    <textarea name="description" id="description" type="text" class="form-control"
                               rows="5">@isset($service){{ $service->description }}@endisset</textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="name">Costo</label>
                    <input type="number" name="price" id="price" class="form-control"
                           value="@isset($service){{ $service->price }}@endisset">
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="name">Costo Dolares</label>
                    <input type="number" name="price_dlls" id="price_dlls" class="form-control"
                           value="@isset($service){{ $service->price_dlls }}@endisset">
                </div>
            </div>

        </form>
    @endslot
    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#services-modal'))">Cancelar</button>
        <button type="button" id="createServiceBtn" onclick="createService()" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
