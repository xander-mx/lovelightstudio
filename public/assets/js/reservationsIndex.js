const PHOTOGRAPHER_MODAL = $('#photographer-modal');
const ACTIONS_MODAL = $('#actions-modal');
const FINALIZE_MODAL = $('#myModalFinalizar');
const CLIENT_MODAL = $('#client-modal');
const CLIENT_FORM = $('#client-form');
const CLIENT_BUTTON = $('#clientBtn');
const FINALIZE_BTN = $('#finalize-btn');

FINALIZE_BTN.click(() => FINALIZE_BTN.prop( "disabled", false ));

const openAction = (id,center =1) => {
    let url = routeEditReservation.replace(':center', center);
    url = url.replace(':id', id);
    document.getElementById("linssk").href = url;
    document.getElementById('reservaCan_2').value= id;
    ACTIONS_MODAL.modal('show');
}

const openModal = (id) => {
    for (let index = 0; index < foto.length; index++) {
        document.getElementById('reserva_'+index).value= id;
    }
    PHOTOGRAPHER_MODAL.modal('show');
}

const openModalFinalizar = (id) => {
    document.getElementById('idReservaFina').value = id;
    FINALIZE_BTN.prop( "disabled", false );
    FINALIZE_MODAL.modal('show');
}

const closeModalFinalizar = () => {
    FINALIZE_MODAL.modal('hide');
}

const openModalClient = (id) => {
    CLIENT_MODAL.find("[name='id']").val(id);
    //let url = routeClientForm.replace(':reservaId', id);
    //CLIENT_FORM.attr('action', url);
    CLIENT_MODAL.modal('show');
}

//# sourceURL=reservationIndex.js