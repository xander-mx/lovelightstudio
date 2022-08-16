const EVENTS_BTN = $('#events-add')
const EVENTS_MODAL =$('#events-modal')
const EVENTS_FORM = $('#events-form')
const EVENTS_BTN_SAVE = $('#createEventBtn')
const EVENTS_TABLE = $('#events-table')
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
        dateValidator: rome.val.afterEq(eventDate)
    })
    SERVICE_FIELD.trigger("change")
    CENTER_FIELD.trigger("change")
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
    let id = $(btn).data("id");
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

//# sourceURL=events.js