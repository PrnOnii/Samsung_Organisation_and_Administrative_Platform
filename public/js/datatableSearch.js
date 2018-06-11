// Setup - add a text input to each footer cell
// $('.dataTable tfoot th').each(function () {
//     var title = $(this).text();
//     $(this).html('<input type="text" placeholder="Search ' + title + '" />');
// });

// DataTable
var table = $('#ajaxStudents').DataTable({
    ajax: "/json",
    "pageLength": 50,
    "columns": [
        { className: "clickable" },
        { className: "clickable" },
        { className: "clickable" },
        { className: "clickable" },
        { className: "clickable" },
        null,
        null
    ],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    }
});
var table = $('.dataTable').DataTable({
    "pageLength": 50,
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    }
});
var refresh;
var checked = 0;
function ajaxRefresh()
{
    checked = $(":checkbox:checked").length;
    if(checked === 0)
    {
        $("#alert-refresh").hide();
        table.ajax.reload();
        $('[data-toggle="tooltip"]').tooltip();
    }
    else
    {
        $("#alert-refresh").show();
    }
}
setInterval(ajaxRefresh, 5000);
// Apply the search
// table.columns().every(function () {
//     var that = this;

//     $('input', this.footer()).on('keyup change', function () {
//         if (that.search() !== this.value) {
//             that
//                 .search(this.value)
//                 .draw();
//         }
//     });
// });