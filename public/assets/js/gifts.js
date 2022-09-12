const GIFTS_MODAL = $('#gifts-modal')
const LINKS_MODAL = $('#links-modal')
const LINKS_FORM = $('#links-form')
const SAVE_LINKS_BTN = $('#captureLinksBtn')
const SAVE_VIDEO_BTN = $('#captureVideoBtn')
const CANVAS_DIV = $('#canvas-div')

LINKS_MODAL.on('hidden.bs.modal', function () {
    clearAnyForm(LINKS_FORM, ['id', 'photos', 'video', 'code', 'email'])
})
GIFTS_MODAL.on('hidden.bs.modal', function() {
    CANVAS_DIV.html('')
})

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

const saveLinks = (video) => {
    let formData = new FormData(document.getElementById("links-form"));
    let routeLinksEventSave = video ? routeVideoEvent.replace( ':id', formData.get('id')) :
        routeLinksEvent.replace( ':id', formData.get('id'))
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

const captureLinks = (btn, video) => {
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
                LINKS_FORM.find("[name='canvas']").val(response.data.canvas);
                LINKS_FORM.find("[name='photobooks']").val(response.data.photobooks);
            }
            hiddenFields(video)
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

const hiddenFields = (hide) => {
    LINKS_FORM.find("[name='photos']").parent('div').parent('div').attr('hidden', hide)
    LINKS_FORM.find("[name='canvas']").parent('div').parent('div').attr('hidden', hide)
    LINKS_FORM.find("[name='photobooks']").parent('div').parent('div').attr('hidden', hide)
    LINKS_FORM.find("[name='video']").parent('div').parent('div').attr('hidden', !hide)
    SAVE_VIDEO_BTN.attr('hidden', !hide)
    SAVE_LINKS_BTN.attr('hidden', hide)
}

//# sourceURL=gifts.js