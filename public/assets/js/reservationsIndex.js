const PHOTOGRAPHER_MODAL = $('#photographer-modal')
const ACTIONS_MODAL = $('#actions-modal')
const FINALIZE_MODAL = $('#myModalFinalizar')
const CLIENT_MODAL = $('#client-modal')
const CLIENT_FORM = $('#client-form')
const CLIENT_BUTTON = $('#clientBtn')
const FINALIZE_BTN = $('#finalize-btn')
const EDIT_RESERVE_MODAL = $('#editReservationModal')
const EDIT_RESERVE_FORM = $('#edit-reservation-form')
const ACTIVES_RESERVES_TABLE = $('#reservations-table')

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

const getInformation = (btn) => {
    let id = $(btn).data('id')
    let routeGetInfoReserveReplace =routeGetInfoReserve.replace(':id', id)
        $.ajax({
            url: routeGetInfoReserveReplace,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
            .done(function(response) {
                EDIT_RESERVE_FORM.find("[name='id']").val(response.idReservation)
                EDIT_RESERVE_FORM.find("[name='customer']").val(response.customer)
                EDIT_RESERVE_FORM.find("[name='pax']").val(response.pax)
                EDIT_RESERVE_FORM.find("[name='email']").val(response.email)
                EDIT_RESERVE_FORM.find("[name='phone']").val(response.phone)
                EDIT_RESERVE_FORM.find("[name='num_habitacion']").val(response.num_habitacion)
                showAnyModal(EDIT_RESERVE_MODAL, 'Editar Reservación')
            })
            .fail(function(jqXHR, textStatus, errorThrown)  {
                Swal.fire({
                    title: 'Ups!',
                    text: jqXHR.responseJSON.message,
                    showConfirmButton: true
                })
            })
}

const editReserve = () => {
    let formData = new FormData(document.getElementById('edit-reservation-form'))

    $.ajax({
        url: routeEditReserve,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function(response) {
            Swal.fire({title:'Exito', text: response.message ,icon: 'success'});
            ACTIVES_RESERVES_TABLE.DataTable().ajax.reload()
            closeAnyModal(EDIT_RESERVE_MODAL)
        })
        .fail(function(jqXHR, textStatus, errorThrown)  {
            Swal.fire({
                title: 'Ups!',
                text: jqXHR.responseJSON.message,
                showConfirmButton: true
            })
        })
        .always(function() {
            $(".content_loading").fadeOut("slow");
        })
}

EDIT_RESERVE_MODAL.on('hidden.bs.modal', function () {
    clearAnyForm(EDIT_RESERVE_FORM, ['id', 'customer', 'pax', 'email', 'phone', 'num_habitacion'])
})
//# sourceURL=reservationIndex.js