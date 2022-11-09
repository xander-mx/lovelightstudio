const PAYROLL_CREATE_BTN = $('#createPayrollBtn')
const PAYROLL_TABLE = $('#payrolls-table')
const NEXT_PAYROLL_TABLE = $('#nextpayroll-table')
const PAYROLL_MODAL_BTN = $('#payroll-add')
const PAYROLL_MODAL =$('#payroll-modal')

PAYROLL_CREATE_BTN.click(() => createPayroll())
PAYROLL_MODAL_BTN.click(() => showAnyModal(PAYROLL_MODAL, 'Corte de Quincena'))

const createPayroll = () => {
    let formData = new FormData(document.getElementById("payroll-form"));
    PAYROLL_CREATE_BTN.prop( "disabled", true )
    $.ajax({
        url: routeCreatePayroll,
        data: formData,
        dataType: 'json',
        type: 'POST',
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
            Swal.fire({title: 'Muy bien!', text:'Arqueo generado!!!', icon:'success'})
            PAYROLL_TABLE.DataTable().ajax.reload()
            NEXT_PAYROLL_TABLE.DataTable().ajax.reload()
        })
        .fail(function(jqXHR, textStatus, errorThrown)  {
            Swal.fire({
                title: 'Ups!',
                text: jqXHR.responseJSON.message,
                showConfirmButton: true
            })
        })
        .always(function() {
            $(".content_loading").fadeOut("slow")
            PAYROLL_CREATE_BTN.prop( "disabled", false )
            closeAnyModal(PAYROLL_MODAL)
        })
}

const updateStatus = (btn) => {
    let id = $(btn).data('id')
    let status = $(btn).data('status')
    let replaceRouteStatusPayroll = routeStatusPayroll.replace(':id', id)
    replaceRouteStatusPayroll = replaceRouteStatusPayroll.replace(':status', status)
    Swal.fire({
        title: 'Cierre de Nómina',
        text: '¿Esta seguro de cerrar la nómina?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Si, cerrar nómina',
        denyButtonText: 'No, cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: replaceRouteStatusPayroll,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(".content_loading").fadeIn("slow");
                }
            })
                .done(function(response) {
                    PAYROLL_TABLE.DataTable().ajax.reload()
                    NEXT_PAYROLL_TABLE.DataTable().ajax.reload()
                    Swal.fire({ title: 'Nómina', text: response.message, showConfirmButton: true})
                })
                .fail(function(jqXHR, textStatus, errorThrown)  {
                    Swal.fire({
                        title: 'Ups!',
                        text: jqXHR.responseJSON.message,
                        showConfirmButton: true
                    })
                })
                .always(function() {
                    $(".content_loading").fadeOut("slow")
                })
        } else if (result.isDenied) {
            Swal.fire(messageCancel, '', 'info')
        }
    })
}