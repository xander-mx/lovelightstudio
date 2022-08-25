@component('templates.modal', ['modalId' => 'links-modal', 'title' => 'Links Evento', 'close' => true,
                               'backdrop' => true, 'modalSize' => 'modal-lg'])
    @slot('body')
        <form id="links-form">
            <input type="hidden" name="id" id="id" />
            <div class="row">
                <div class="form-group col-12">
                    <label for="photos">Link Fotos</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-images"></i>
                        </span>
                        <input name="photos" id="photos" type="text" class="form-control" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12">
                    <label for="video">Link Video</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-camera-video"></i>
                        </span>
                        <input name="video" id="video" type="text" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="code">Código</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-key"></i>
                        </span>
                        <input name="code" id="code" type="text" class="form-control" />
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="email">Email extra</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-plus"></i>
                        </span>
                        <input name="email" id="email" type="text" class="form-control" />
                    </div>
                </div>
            </div>
        </form>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-danger" onclick="closeAnyModal($('#links-modal'))">Cancelar</button>
        <button type="button" id="captureLinksBtn" onclick="saveLinks()" class="btn btn-primary">Aceptar</button>
    @endslot
@endcomponent
