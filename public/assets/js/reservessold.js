
const INIT_DATE = $('#initDate')
const END_DATE = $('#endDate')
const BTN_SEARCH_SOLDS = $('#searchSolds')
const TABLE_SOLDS = $('#sold-table')

BTN_SEARCH_SOLDS.click(() => TABLE_SOLDS.DataTable().ajax.reload())
const exportTableToExcel = (tableID, filename = '') => {
    let downloadLink;
    let dataType = 'application/vnd.ms-excel';
    let tableSelect = document.getElementById(tableID);
    let tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        let blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        // Setting the file name
        downloadLink.download = filename;
        //triggering the function
        downloadLink.click();
    }
}

rome(initDate, {
    time: false,
    styles: {
        container: "rd-container datepicker-rome "
    },
    initialValue: moment().format('YYYY-MM-DD'),
    autoHideOnBlur: false
})

rome(endDate, {
    time: false,
    styles: {
        container: "rd-container datepicker-rome "
    },
    initialValue: moment().format('YYYY-MM-DD'),
    dateValidator: rome.val.afterEq(INIT_DATE.val()),
    autoHideOnBlur: false
})

INIT_DATE.blur((event) => {
    rome(endDate).destroy()
    rome(endDate).restore({
        time: false,
        initialValue: event.target.value,
        dateValidator: rome.val.afterEq(event.target.value)
    })
})

const filter_dates_fn = (d) => {
    d.init_date = INIT_DATE.val()
    d.end_date = END_DATE.val()
    return d
}

//# sourceURL=reservesSolds.js