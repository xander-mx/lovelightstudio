const CONFIGURATION_EDIT_BTN = $('#editConfigurationBtn')
const CONFIGURATION_TABLE = $('#configurations-table')
const CONFIGURATION_FORM = $('#configuration-form')
const CONFIGURATION_MODAL = $('#configuration-modal')

CONFIGURATION_EDIT_BTN.click(() => saveConfiguration())

const saveConfiguration = () => {
    let formData = new FormData(document.getElementById("configuration-form"));
    CONFIGURATION_EDIT_BTN.prop( "disabled", true )

    $.ajax ({
        url: routeUpdate,
        type: 'POST',
        data: formData,
        dataType: 'json',
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
            if (response.code === 200) {
                Swal.fire({title: 'Muy bien!', text: response.message, icon:'success'})
                CONFIGURATION_TABLE.DataTable().ajax.reload();
            }else if (response.code === 400) {
                showAjaxErrors(response)
            }else {
                Swal.fire({title: 'Ops!', text:response.message, icon:'error'})
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown)  {
            Swal.fire({
                title: 'Ups!',
                text: jqXHR.responseJSON.message,
                type: "warning",
                animation: false,
                showConfirmButton: true
            })
        })
        .always(function() {
            $(".content_loading").fadeOut("slow");
            CONFIGURATION_EDIT_BTN.prop( "disabled", false );
            CONFIGURATION_MODAL.modal('toggle');
        })
}

const editConfiguration = (btn) => {
    let id = $(btn).data('id')
    let routeGetInformationReplace = routeGetInformation.replace(':id', id)
    $.ajax({
        url: routeGetInformationReplace,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function (response){
            CONFIGURATION_FORM.find("[name='id']").val(response.data.id);
            CONFIGURATION_FORM.find("[name='name']").val(response.data.name);
            CONFIGURATION_FORM.find("[name='value']").val(response.data.value);
            showAnyModal(CONFIGURATION_MODAL, 'Editar Configuración')
        })
        .fail(function(jqXHR, textStatus, errorThrown)  {
            Swal.fire({
                title: 'Ups!',
                text: jqXHR.responseJSON.message,
                type: "warning",
                animation: false,
                showConfirmButton: true
            })
        })
        .always(function() {
            $(".content_loading").fadeOut("slow");
        })
}