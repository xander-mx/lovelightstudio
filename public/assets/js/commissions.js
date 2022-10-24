const EMPLOYEE_SELECT = $('#employee')
const COMMISSION_TABLE = $('#commissions-table')
const COMMISSION_BTN = $('#commission-add')
const COMMISSION_MODAL = $('#commissions-modal')

EMPLOYEE_SELECT.change((event) => {
    let table = COMMISSION_TABLE.DataTable().ajax.reload()
})

const getEmployee_fn = (d) => {
    d.employee = $('#employee').val()
    return d
}

COMMISSION_BTN.click(() => showAnyModal(COMMISSION_MODAL, 'Nueva Comisión'))

//# sourceURL=commissions.js