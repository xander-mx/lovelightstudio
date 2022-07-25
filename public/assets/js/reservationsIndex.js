const PHOTOGRAPHER_MODAL = $('#photographer-modal');
const ACTIONS_MODAL = $('#actions-modal');
const FINALIZE_MODAL = $('#myModalFinalizar');

const openAction = (id,center =1) => {
    let url = routeEditReservation.replace(':center', center);
    url = url.replace(':id', id);
    document.getElementById("linssk").href = url;
    document.getElementById('reservaCan_2').value= id;
    ACTIONS_MODAL.modal('show');
}

const closeAction = () => {
    ACTIONS_MODAL.modal('hide');
}

const cerrarModal = () => {
    PHOTOGRAPHER_MODAL.modal('hide');
}

const openModal = (id) => {
    for (let index = 0; index < foto.length; index++) {
        document.getElementById('reserva_'+index).value= id;
    }
    PHOTOGRAPHER_MODAL.modal('show');
}

const openModalFinalizar = (id) => {
    document.getElementById('idReservaFina').value = id;
    FINALIZE_MODAL.modal('show');
}

const closeModalFinalizar = () => {
    FINALIZE_MODAL.modal('hide');
}

//# sourceURL=reservationIndex.js