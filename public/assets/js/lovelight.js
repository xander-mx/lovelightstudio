
const closeAnyModal = (element) => {
    element.modal('hide');
}

const showAnyModal = (element, title) => {
    element.find('.modal-title').text(title)
    element.modal('show')
}

const questionModal = (title, deny, cancel, textConfirm, denyText = '', fnConfirm, messageCancel) => {
    Swal.fire({
        title: title,
        showDenyButton: deny,
        showCancelButton: cancel,
        confirmButtonText: textConfirm,
        denyButtonText: denyText,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            fnConfirm
            Swal.fire('Saved!', '', 'success')
        } else if (result.isDenied) {
            Swal.fire(messageCancel, '', 'info')
        }
    })
}

let dollarUSLocale = new Intl.NumberFormat('en-US',{ style: 'currency', currency: 'USD'})

const showAjaxErrors = (response) => {
    let datos = response.data
    let message = ""
    Object.entries(datos).forEach(([key, value]) => {
        value.forEach((error) => {
            message += "- " + error + "\n";
        })
    });
    Swal.fire({title: 'Error',text: message, icon:'error'})
}

const deleteCustomEntity = (btn, route, entity, datatable) => {
    let id = $(btn).data('id');
    let routeDeleteReplace = route.replace(':id', id);
    Swal.fire({
        title: 'Eliminar',
        text: '¿Esta seguro de eliminar ' +entity+'?',
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
                url: routeDeleteReplace,
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
                    datatable.DataTable().ajax.reload();
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