const SERVICE_FORM = $('#services-form');
const SERVICE_BTN = $('#services-add');
const SERVICE_MODAL = $('#services-modal');
const SERVICE_TABLE = $('#services-table');

SERVICE_BTN.click(() => showAnyModal(SERVICE_MODAL, 'Nuevo Paquete'))

SERVICE_MODAL.on('hidden.bs.modal', function () {
    SERVICE_FORM.find("[name = 'id']").val('')
    SERVICE_FORM.find("[name = 'name']").val('')
    SERVICE_FORM.find("[name = 'description']").val('')
    SERVICE_FORM.find("[name = 'price']").val('')
    SERVICE_FORM.find("[name = 'hours']").val('')
})

const createService = () => {
    let formData = new FormData(document.getElementById("services-form"));
    SERVICE_BTN.prop( "disabled", true );

    $.ajax ({
        url: routeSaveServices,
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
                Swal.fire({title: 'Muy bien!', text:'El paquete se creó correctamente', icon:'success'});
                SERVICE_TABLE.DataTable().ajax.reload();
            }else if (response.code === 400) {
                let datos = response.data
                let message = ""
                Object.entries(datos).forEach(([key, value]) => {
                    value.forEach((error) => {
                        message += "- " + error + "\n";
                    })
                });
                Swal.fire({title: 'Error',text: message, icon:'error'})
            } else {
                Swal('Ops!', response.message, 'error')
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
            SERVICE_BTN.prop( "disabled", false );
            SERVICE_MODAL.modal('toggle');
        })
}

const editService = (btn) => {
    let id = $(btn).data("id");
    let routeGetInformationreplace = routeGetService.replace(':id', id);

    $.ajax({
        url: routeGetInformationreplace,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $(".content_loading").fadeIn("slow")
        }
    })
        .done(function(response) {
            SERVICE_FORM.find("[name='id']").val(response.data.id)
            SERVICE_FORM.find("[name='name']").val(response.data.name)
            SERVICE_FORM.find("[name='description']").val(response.data.description)
            SERVICE_FORM.find("[name='price']").val(response.data.price)
            SERVICE_FORM.find("[name='hours']").val(response.data.hours)
            showAnyModal(SERVICE_MODAL, 'Editar Paquete')
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

const deleteService = (btn) => {
    deleteCustomEntity(btn, routeDeleteService, 'el paquete', SERVICE_TABLE)
}

//# sourceURL=services.js