const EVENTS_BTN = $('#events-add')
const EVENTS_MODAL = $('#events-modal')
const ADD_PAYMENT_MODAL =$('#add-payment-modal')
const INFO_PAYMENT_MODAL =$('#info-payment-modal')
const GIFTS_MODAL = $('#gifts-modal')
const LINKS_MODAL = $('#links-modal')
const ADD_PAYMENT_FORM = $('#events-payments-form')
const LINKS_FORM = $('#links-form')
const ADD_PAYMENT_BTN = $('#addPaymentEventBtn')
const SAVE_LINKS_BTN = $('#captureLinksBtn')
const EVENTS_FORM = $('#events-form')
const EVENTS_BTN_SAVE = $('#createEventBtn')
const EVENTS_TABLE = $('#events-table')
const INFO_PAYMENTS_TABLE =$('#info-payments-table')
const CANVAS_DIV = $('#canvas-div')
const SERVICE_FIELD = EVENTS_FORM.find("[name = 'service']")
const CENTER_FIELD = EVENTS_FORM.find("[name = 'center']")
const PRICE_FIELD = EVENTS_FORM.find("[name = 'price']")
const HOTEL_FIELD = EVENTS_FORM.find("[name = 'hotel']")
const PERSONALIZE_FIELD = EVENTS_FORM.find("[name = 'personalize']")
const STATUS_FILTER = $('#status')

const getDataServices = (id) => {
    if(Number(id) !== service_personalize) {
        let routeGetInformationreplace = routeGetDataService.replace(':id', id);
        $.ajax({
            url: routeGetInformationreplace,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
            .done(function(response) {
                PRICE_FIELD.val(dollarUSLocale.format(response.data.price))
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
    } else {
        if(EVENTS_FORM.find("[name = 'id']").val() === '')
            PRICE_FIELD.val("")
    }
}

const getStatus_fn = (d) => {
    d.status = STATUS_FILTER.val()
    return d
}

const getEvent_fn = (d) => {
    d.event = $('#id_event_payment').val()
    return d
}

ADD_PAYMENT_BTN.click(() => saveAddPayment())
EVENTS_BTN.click(() => showAnyModal(EVENTS_MODAL, 'Nuevo Evento'))
SERVICE_FIELD.change((event) => {
    PERSONALIZE_FIELD.attr("readonly", Number(event.target.value) !== service_personalize)
    PRICE_FIELD.attr("readonly", Number(event.target.value) !== service_personalize)
    if(Number(event.target.value) !== service_personalize) PERSONALIZE_FIELD.val('')
    getDataServices(event.target.value);
})

CENTER_FIELD.change((event) => {
    HOTEL_FIELD.attr("readonly", Number(event.target.value) !== externalHotel)
    if(Number(event.target.value) !== externalHotel) HOTEL_FIELD.val('')
})

STATUS_FILTER.change(() => {
    EVENTS_TABLE.DataTable().ajax.reload()
})

EVENTS_MODAL.on('shown.bs.modal', function () {
    rome(eventDate, {
        time: false,
        styles: {
            container: "rd-container datepicker-rome"
        },
        initialValue: moment().format('YYYY-MM-DD'),
        dateValidator: rome.val.afterEq(eventDate),
        autoHideOnBlur: false
    })
    SERVICE_FIELD.trigger("change")
    CENTER_FIELD.trigger("change")
})

ADD_PAYMENT_MODAL.on('shown.bs.modal', function () {
    rome(paymentDate, {
        time: false,
        styles: {
            container: "rd-container datepicker-rome"
        },
        initialValue: moment().format('YYYY-MM-DD'),
        dateValidator: rome.val.afterEq(eventDate),
        autoHideOnBlur: false
    })
})

INFO_PAYMENT_MODAL.on('shown.bs.modal', function() {
    INFO_PAYMENTS_TABLE.DataTable().ajax.url(routeInfoPaymentsRender)
    INFO_PAYMENTS_TABLE.DataTable().ajax.reload()
})

EVENTS_MODAL.on('hidden.bs.modal', function () {
    clearAnyForm(EVENTS_FORM, ['id', 'customer', 'phone', 'email', 'price', 'discount', 'personalize'])
})

const saveEvent = () => {
    let formData = new FormData(document.getElementById("events-form"));
    EVENTS_BTN_SAVE.prop( "disabled", true );

    $.ajax ({
        url: routeSaveEvent,
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
                Swal.fire({title: 'Muy bien!', text:'El evento se creó correctamente', icon:'success'})
                EVENTS_TABLE.DataTable().ajax.reload();
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
            EVENTS_BTN_SAVE.prop( "disabled", false );
            EVENTS_MODAL.modal('toggle');
        })
}

const editEvent = (btn) => {
    let id = $(btn).data("id")
    let routeGetInformationreplace = routeGetEvent.replace(':id', id);

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
            EVENTS_FORM.find("[name='id']").val(response.data.id);
            EVENTS_FORM.find("[name='customer']").val(response.data.customer);
            console.log(moment(new Date(response.data.event_date)).format('YYYY-MM-DD'))
            CENTER_FIELD.val(response.data.id_center).change();
            SERVICE_FIELD.val(response.data.id_service).change();
            if(response.data.id_service === service_personalize) {
                EVENTS_FORM.find("[name='price']").val(response.data.price.toString());
            }
            EVENTS_FORM.find("[name='phone']").val(response.data.phone);
            EVENTS_FORM.find("[name='email']").val(response.data.email);
            EVENTS_FORM.find("[name='personalize']").val(response.data.personalize);
            EVENTS_FORM.find("[name = 'discount']").val(response.data.discount)
            EVENTS_FORM.find("[name = 'eventDate']").val(moment(new Date(response.data.event_date)).format('YYYY-MM-DD'))
            showAnyModal(EVENTS_MODAL, 'Editar Evento')
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

const deleteEvent = (btn) => {
    deleteCustomEntity(btn, routeDeleteEvent, 'el evento', EVENTS_TABLE)
}

const addPayment = (btn) => {
    let id = $(btn).data("id")
    showAnyModal(ADD_PAYMENT_MODAL, 'Agregar Pago')
    ADD_PAYMENT_FORM.find("[ name = 'id_event']").val(id)
}

const saveAddPayment = () => {
    let formData = new FormData(document.getElementById("events-payments-form"));
    ADD_PAYMENT_BTN.prop( "disabled", true );

    $.ajax ({
        url: routeAddPaymentSave,
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
                EVENTS_TABLE.DataTable().ajax.reload();
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
            ADD_PAYMENT_BTN.prop( "disabled", false );
            ADD_PAYMENT_MODAL.modal('toggle');
        })
}

const infoPayment = (btn) => {
    let id = $(btn).data("id")
    let customer = $(btn).data("customer")
    routeInfoPaymentsRender = routeInfoPayments.replace(':id',id)
    showAnyModal(INFO_PAYMENT_MODAL, customer)
}

ADD_PAYMENT_MODAL.on('hidden.bs.modal', function () {
    clearAnyForm(ADD_PAYMENT_FORM, ['id_event', 'amount', 'voucher', 'event'])
})

LINKS_MODAL.on('hidden.bs.modal', function () {
    clearAnyForm(LINKS_FORM, ['id', 'photos', 'video', 'code', 'email'])
})

const captureLinks = (btn) => {
    let id = $(btn).data("id")
    LINKS_FORM.find("[name='id']").val(id)
    let routeGetInformationreplace = routeGetEvent.replace(':id', id);

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
            if(response.code === 200) {
                LINKS_FORM.find("[name='photos']").val(response.data.photos);
                LINKS_FORM.find("[name='video']").val(response.data.video);
                LINKS_FORM.find("[name='code']").val(response.data.code);
                LINKS_FORM.find("[name='canvas']").val(response.data.canvas);
                LINKS_FORM.find("[name='photobooks']").val(response.data.photobooks);
            }
            showAnyModal(LINKS_MODAL,'Links Evento')
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

const saveLinks = () => {
    let formData = new FormData(document.getElementById("links-form"));
    let routeLinksEventSave = routeLinksEvent.replace( ':id', formData.get('id'))
    $.ajax ({
        url: routeLinksEventSave,
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
            SAVE_LINKS_BTN.prop( "disabled", true );
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function(response) {
            if (response.code === 200) {
                Swal.fire({title: 'Muy bien!', text:'Se enviarón links', icon:'success'})
            }else if (response.code === 400) {
                showAjaxErrors(response)
            }else {
                Swal.fire({title: 'Ops!', text:response.message, icon:'error'})
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown)  {
            Swal.fire({
                title: 'Ups!',
                text: jqXHR.statusText,
                type: "warning",
                animation: false,
                showConfirmButton: true
            })
        })
        .always(function() {
            $(".content_loading").fadeOut("slow");
            SAVE_LINKS_BTN.prop( "disabled", false );
            LINKS_MODAL.modal('toggle');
        })
}

const finalizeEvent = (btn) => {
    let id = $(btn).data("id")
    let routeFinalizeEventComplete = routeFinalizeEvent.replace(':id', id)

    Swal.fire({
        title: 'Finalizar',
        text: '¿Esta seguro de cerrar el evento?',
        icon: 'warning',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fa fa-thumbs-up"></i>  Si, cerrar',
        cancelButtonText: '<i class="fa fa-thumbs-down"></i> No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: routeFinalizeEventComplete,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(".content_loading").fadeIn("slow");
                }
            })
                .done(function(response) {
                    EVENTS_TABLE.DataTable().ajax.reload();
                    Swal.fire({title: 'Muy bien!', text:'Se cerro el evento', icon:'success'})
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

const obtainGifts = (btn) => {
    let id = $(btn).data("id")
    let routeGiftsObtainComplete = routeGiftsObtain.replace(':id', id)

    $.ajax({
        url: routeGiftsObtainComplete,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function(response) {
            let gifts = response.data
            gifts.forEach( g => {
                CANVAS_DIV.append(g)
            })
            showAnyModal(GIFTS_MODAL, 'Canvas y Photobooks')
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

GIFTS_MODAL.on('hidden.bs.modal', function() {
    CANVAS_DIV.html('')
})

//# sourceURL=events.js