@component('templates.modal', ['modalId' => 'client-modal', 'title' => 'Datos del Cliente', 'close' => true])
    @slot('body')
        <form id="client-form" action="{{ route('reserva.info') }}">
            <input type="hidden" id="id" name="id" />
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" required />
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="email2" class="form-label">Email 2  </label>
                    <input type="email" class="form-control" name="email2" id="email2" aria-describedby="emailHelp" />
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="tel1" class="form-label">Teléfono </label>
                    <input type="text" class="form-control" name="tel1" id="tel1" aria-describedby="tel1" value="+52" />
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="tel2" class="form-label">Telefono 2  </label>
                    <input type="text" class="form-control" name="tel2" id="tel2" aria-describedby="tel2" value="+52" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="canva" class="form-label">Canva </label>
                    <input type="text"  class="form-control" name="canva" id="canva" aria-describedby="canva" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text"  class="form-control" name="direccion" id="direccion" aria-describedby="direccion" />
                </div>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" id="clientBtn" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    @endslot
@endcomponent
