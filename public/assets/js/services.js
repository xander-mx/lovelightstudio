const SERVICE_FORM = $('#services-form');
const SERVICE_BTN = $('#services-add');
const SERVICE_MODAL = $('#services-modal');
const SERVICE_TABLE = $('#services-table');

SERVICE_BTN.click(() => SERVICE_MODAL.modal('show'));

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
                swal('Ops!', response.message, 'error')
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
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function(response) {
            SERVICE_FORM.find("[name='id']").val(response.data.id);
            SERVICE_FORM.find("[name='name']").val(response.data.name);
            SERVICE_FORM.find("[name='description']").val(response.data.description);
            SERVICE_FORM.find("[name='price']").val(response.data.price);
            SERVICE_FORM.find("[name='price_dlls']").val(response.data.price_dlls);
            SERVICE_MODAL.modal('show');
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
    let id = $(btn).data('id');
    let routeDeleteServiceReplace = routeDeleteService.replace(':id', id);
    Swal.fire({
        title: 'Eliminar',
        text: '¿Esta seguro de eliminar el Paquete?',
        icon: 'warning',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fa fa-thumbs-up"></i>  Si, eliminar',
        cancelButtonText: '<i class="fa fa-thumbs-down"></i> No, cancelar'
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: routeDeleteServiceReplace,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(".content_loading").fadeIn("slow");
                }
            })
                .done(function(response) {
                    Swal.fire({title:'Exitoso', text: response.message,icon: 'success'});
                    SERVICE_TABLE.DataTable().ajax.reload();
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
            Swal.fire('Saved!', '', 'success')
        } else if (result.isDenied) {
            Swal.fire('Se cancelo la eliminación', '', 'info')
        }
    })
}

//# sourceURL=services.js