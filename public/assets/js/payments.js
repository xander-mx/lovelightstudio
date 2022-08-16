const PAYMENTS_FORM = $('#payments-form')
const PAYMENTS_ADD_BTN = $('#payments-add')
const PAYMENTS_MODAL = $('#payments-modal')
const PAYMENT_TYPE_FIELD = PAYMENTS_FORM.find("[name = 'payment_type']")
const VOUCHER_FIELD = PAYMENTS_FORM.find("[name = 'voucher']")
const AMOUNT_FIELD = PAYMENTS_FORM.find("[name = 'amount']")
const PAYMENTS_SAVE_BTN = $('#createPaymentBtn')
const PAYMENTS_TABLE = $('#payments-table')

PAYMENTS_ADD_BTN.click(() => showAnyModal(PAYMENTS_MODAL, 'Nuevo Pago'))
PAYMENTS_SAVE_BTN.click(() => savePayment())

$('#event').autocomplete({
    source: (search, response) => {
        let routeAutocomplete = routeEventAucomplete.replace(':search', search.term)
        $.ajax({
          url: routeAutocomplete,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
            .done((data) => {
                response(data)
            })
    },
    minLength: 3,
    select: (event, ui) => {
        PAYMENT_TYPE_FIELD.attr("disabled", false)
        VOUCHER_FIELD.attr("readonly", false)
        AMOUNT_FIELD.attr("readonly", false)
        PAYMENTS_FORM.find("[name = 'id_event']").val(ui.item.event)
        PAYMENTS_FORM.find("[name = 'paymentDate']").attr("readonly", false)
    },
    change: (event, ui) => {
        if(!ui.item) {
            PAYMENT_TYPE_FIELD.attr("disabled", true)
            VOUCHER_FIELD.attr("readonly", true).val('')
            AMOUNT_FIELD.attr("readonly", true).val('')
            PAYMENTS_FORM.find("[name = 'id_event']").val('')
        }
    }
})

const savePayment = () => {
    let formData = new FormData(document.getElementById("payments-form"));
    PAYMENTS_SAVE_BTN.prop( "disabled", true );

    $.ajax ({
        url: routePaymentSave,
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
                Swal.fire({title: 'Muy bien!', text:'El pago se creó correctamente', icon:'success'})
                PAYMENTS_TABLE.DataTable().ajax.reload();
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
            PAYMENTS_SAVE_BTN.prop( "disabled", false );
            PAYMENTS_MODAL.modal('toggle');
        })
}

const editPayment = (btn) => {
    let id = $(btn).data("id");
    let routeGetInformationPaymentreplace = routePaymentGetInformation.replace(':id', id);

    $.ajax({
        url: routeGetInformationPaymentreplace,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function(response) {
            if(response.code === 200) {
                PAYMENTS_FORM.find("[name='id']").val(response.data.id);
                PAYMENTS_FORM.find("[name='event']").val(response.data.event.customer);
                PAYMENTS_FORM.find("[name='id_event']").val(response.data.id_event);
                PAYMENTS_FORM.find("[name = 'event']").attr("readonly", true)
                PAYMENT_TYPE_FIELD.val(response.data.payment_type).attr("disabled", false)
                AMOUNT_FIELD.val(response.data.amount).attr("readonly", false)
                VOUCHER_FIELD.val(response.data.voucher).attr("readonly", false)
                PAYMENTS_FORM.find("[name = 'paymentDate']")
                    .val(moment(new Date(response.data.payment_date)).format('YYYY-MM-DD')).attr("readonly", false)
                showAnyModal(PAYMENTS_MODAL, 'Editar Pago')
            } else {
                Swal.fire({
                    title: 'Ups!',
                    text: response.message,
                    type: "warning",
                    animation: false,
                    showConfirmButton: true
                })
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
        })
}

PAYMENTS_MODAL.on('hidden.bs.modal', function () {
    clearAnyForm(PAYMENTS_FORM, ['id', 'id_event', 'amount', 'voucher', 'event'])
    AMOUNT_FIELD.attr("readonly", true)
    VOUCHER_FIELD.attr("readonly", true)
    PAYMENT_TYPE_FIELD.attr("disabled", true)
    PAYMENTS_FORM.find("[name = 'paymentDate']").attr("readonly", true)
})

PAYMENTS_MODAL.on('shown.bs.modal', function () {
    rome(paymentDate, {
        time: false,
        styles: {
            container: "rd-container datepicker-rome "
        },
        initialValue: moment().format('YYYY-MM-DD'),
        dateValidator: rome.val.afterEq(moment().format('YYYY-MM-DD')),
        autoHideOnBlur: false
    })
})

const deletePayment = (btn) => {
    deleteCustomEntity(btn, routePaymentDelete, 'el pago', PAYMENTS_TABLE)
}

//# sourceURL=payments.js