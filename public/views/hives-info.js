// Defer script loading to improve performances
$.getScript('js/dataTables.bootstrap5.min.js').done(() => {
    // noinspection ES6ConvertVarToLetConst

    var datatable = $('#hives-list').DataTable({
        ajax: {url: 'api/data.php', dataSrc: 'data'},
        deferRender: true,
        serverSide: true,
        columns: [
            {title: "Ruche", data: 'hive.name'},
            {
                title: "Date", data: 'date',
                render: function (data, type, row) {
                    const parsedDate = new Date(Date.parse(row.date.date));
                    const date = parsedDate.toLocaleDateString('fr-fr', {day: 'numeric', month: 'long', year: 'numeric'})
                    const hour = parsedDate.toLocaleTimeString('fr-fr', {hour:'numeric', minute:'numeric'})
                    return date + ' ' +  hour.replace(':', 'h');
                }
            },
            {title: "Poids", data: 'weight'},
            {title: "Température", data: 'temperature'},
            {title: "Humidité", data: 'humidity'}
        ],
        language: {
            url: 'assets/fr_fr.json'
        },
        dom: "<'row'<'col-sm-12'tr>>" +
            "<'row align-items-center'<'col-sm-12 col-md-5 datatable-info-padding'il><'col-sm-12 col-md-7'p>>"
    });

    // Handler for the external search field
    $('#hive-search-field').keyup(function(){
        datatable.search($(this).val()).draw() ;
    })
});

