

const closeAnyModal = (element) => {
    element.modal('hide');
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
