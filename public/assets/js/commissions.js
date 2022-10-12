const EMPLOYEE_SELECT = $('#employee')
const COMMISSION_TABLE = $('#commissions-table');

EMPLOYEE_SELECT.change((event) => {
    let table = COMMISSION_TABLE.DataTable().ajax.reload()
})

const getEmployee_fn = (d) => {
    d.employee = $('#employee').val()
    return d
}

//# sourceURL=commissions.js