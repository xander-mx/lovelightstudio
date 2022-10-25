const EMPLOYEE_SELECT = $('#employee')
const COMMISSION_TABLE = $('#commissions-table')
const COMMISSION_BTN = $('#commission-add')
const COMMISSION_MODAL = $('#commissions-modal')
const COMMISSION_SAVE_BTN = $('#createCommissionBtn')

EMPLOYEE_SELECT.change((event) => {
    let table = COMMISSION_TABLE.DataTable().ajax.reload()
})

const getEmployee_fn = (d) => {
    d.employee = $('#employee').val()
    return d
}

COMMISSION_BTN.click(() => showAnyModal(COMMISSION_MODAL, 'Nueva Comisión'))

const saveCommission = () => {
    let formData = new FormData(document.getElementById("commissions-form"))
    COMMISSION_SAVE_BTN.prop( "disabled", true )

    $.ajax ({
        url: routeCommissionSave,
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
    }).done(function(response) {
        if (response.code === 200) {
            Swal.fire({title: 'Muy bien!', text:'La comisión se creó correctamente', icon:'success'})
            COMMISSION_TABLE.DataTable().ajax.reload();
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
            $(".content_loading").fadeOut("slow")
            COMMISSION_SAVE_BTN.prop( "disabled", false)
            COMMISSION_MODAL.modal('toggle')
        })
}

const statusCommission = (btn) => {
    let id = $(btn).data("id")
    let status = $(btn).data("status")
    let title = status === 'RECHAZADO' ? 'Rechazar' : 'Autorizar';
    let routeCommissionStatusreplace = routeCommissionStatus.replace(':status', status)
    routeCommissionStatusreplace = routeCommissionStatusreplace.replace(':id', id)

    Swal.fire({
        title: title,
        text: '¿Esta seguro de '+ title.toLowerCase() +' la comisión?',
        icon: 'warning',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fa fa-thumbs-up"></i>  Si, '+ title.toLowerCase(),
        cancelButtonText: '<i class="fa fa-thumbs-down"></i> No, regresar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: routeCommissionStatusreplace,
                type: 'put',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(".content_loading").fadeIn("slow");
                }
            })
                .done(function(response) {
                    Swal.fire({title:'Exitoso', text: response.message,icon: 'success'});
                    COMMISSION_TABLE.DataTable().ajax.reload();
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
        } else if (result.isDenied) {
            Swal.fire('Se cancelo ', '', 'info')
        }
    })
}

//# sourceURL=commissions.js