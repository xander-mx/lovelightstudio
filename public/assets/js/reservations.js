const RESERVATION_FORM = $('#reservation-form');
const RESERVATION_CREATE_BTN = $('#createReservationBtn');
const RESERVATION_MODAL = $('#modalReserva');
const RESERVATION_DATATABLE = $('#reservation-table').DataTable();

const createReserva = () => {
    let formData = new FormData(document.getElementById("reservation-form"));
    formData.append("date", fechaSelected);
    formData.append("idCenter", RESERVATION_FORM.find("[name = 'idCenter']").val());
    RESERVATION_CREATE_BTN.prop( "disabled", true );
    $.ajax({
        url: routeReservaStore,
        data: formData,
        type: 'post',
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
            console.log(response)
            if (response.code === 200) {
                Swal.fire({title: 'Muy bien!', text: 'La reserva se ha realizado correctamente', icon:'success'});
                let table = $('#reservation-table').DataTable();
                table.row.add(response.data).draw();
                setTimeout(() => {
                    window.location = routeReservaIndex
                }, 1000);
            } else if (response.code === 400) {
                let datos = response.data
                let message = ""
                Object.entries(datos).forEach(([key, value]) => {
                    value.forEach((error) => {
                        message += "- " + error + "\n";
                    })
                });
                Swal.fire({text: message, icon: 'error'})
            } else {
                Swal.fire({title: 'Ops!', text: response.message, icon:'error'})
            }
        })
        .fail(function(xhr) {
            $(".content_loading").fadeOut("slow");
            Swal.fire({
                title: 'Ups!',
                text: xhr.message,
                type: "warning",
                animation: false,
                showConfirmButton: true
            });
        })
        .always(function() {
            $(".content_loading").fadeOut("slow");
            RESERVATION_CREATE_BTN.prop( "disabled", false );
            RESERVATION_MODAL.modal('toggle');
        });
}

const selectDay = (day) => {
    let ids = RESERVATION_FORM.find("[name = 'idCenter']").val();

    $.ajax({
        url: routeReservaSchedule,
        type: 'GET',
        data: {
            day: day,
            idCenter: ids
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $(".content_loading").fadeIn("slow");
        }
    })
        .done(function(response) {
            console.log(response)
            RESERVATION_DATATABLE.clear().draw();
            RESERVATION_DATATABLE.rows.add(response.table).draw();
            $(".content_loading").fadeOut("slow");
            showHorarios(response.data);
            fechaSelected = day;
        })
        .fail(function(xhr) {
            $(".content_loading").fadeOut("slow");
            Swal.fire({ tilte: 'Ops!', text: 'Hubo un error, por favor intentelo más tarde', icon: 'error'})
        })
}

const showHorarios = (schedule) => {
    $('#mañana').empty()
    $('#mediodia').empty()
    $('#tarde').empty()
    schedule.forEach((value) => {
        if (value.hour < 12) {
            $('#mañana').append(`
                            <a href="Javascript:;" onclick="openModalReserva(${value.idSchedule})" class="btn btn-primary active"> ${value.hour}:${value.minute}</a>
                        `)
        } else if (value.hour >= 12 && value.hour < 18) {
            $('#mediodia').append(`
                            <a href="Javascript:;" onclick="openModalReserva(${value.idSchedule})" class="btn btn-primary active"> ${value.hour}:${value.minute}</a>
                        `)
        } else {
            $('#tarde').append(`
                            <a href="Javascript:;" onclick="openModalReserva(${value.idSchedule})" class="btn btn-primary active"> ${value.hour}:${value.minute}</a>
                        `)
        }
    });
}

const openModalReserva = (id) => {
    if (id != null && fechaSelected !== "") {
        $('#idSchedule').val(id);
        RESERVATION_MODAL.modal('show');
    } else {
        if (id == null) {
            Swal.fire({title: 'Ops!', text:'Por favor seleccione un horario', icon:'error'})
        } else {
            Swal.fire({title: 'Ops!', text: 'Por favor seleccione una fecha',icon: 'error'})
        }
    }
}

const closeModalReserva = () => {
    RESERVATION_MODAL.modal('hide');
}

//# sourceURL=reservations.js