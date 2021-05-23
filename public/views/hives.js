// noinspection ES6ConvertVarToLetConst

// Defer script loading to improve performances
$.getScript('js/dataTables.bootstrap5.min.js').done(() => {
    var datatable = $('#hives-list').DataTable({
        ajax: {url: 'api/hive.php', dataSrc: ''},
        columns: [
            {title: "Nom", data: 'name'},
            {title: "Latitude", data: 'latitude'},
            {title: "Longitude", data: 'longitude'},
            {
                title: "",
                orderable: false,
                render: function (data, type, row, meta) {
                    const hiveData = encodeURIComponent(JSON.stringify({...row, row: meta.row}));
                    return `<a href="#" data-bs-toggle="modal" data-bs-target="#hive-edit" data-bs-action="edit" data-bs-hive="${hiveData}">Modifier</a> / <a href="#" data-bs-toggle="modal" data-bs-target="#hive-delete" data-bs-hive="${hiveData}"">Supprimer</a>`;
                }
            },
        ],
        language: {
            url: 'assets/fr_fr.json'
        },
        dom: "<'row'<'col-sm-12 w-100'tr>>" +
            "<'row align-items-center'<'col-sm-12 col-md-5 datatable-info-padding'il><'col-sm-12 col-md-7 p-2'p>>"
    });

    // Handler for the external search field
    $('#hive-search-field').keyup(function () {
        datatable.search($(this).val()).draw();
    })
});

var hiveDetailModal = document.getElementById('hive-edit');
hiveDetailModal.addEventListener('show.bs.modal', function (event) {

    const title = hiveDetailModal.querySelector('.modal-title')
    const nameInput = hiveDetailModal.querySelector('#hive-modal-name')
    const latitudeInput = hiveDetailModal.querySelector('#hive-modal-latitude')
    const longitudeInput = hiveDetailModal.querySelector('#hive-modal-longitude')
    const submitButton = hiveDetailModal.querySelector('#hive-modal-save')
    const button = event.relatedTarget
    const hive = JSON.parse(decodeURIComponent(button.getAttribute('data-bs-hive'))); // Extract info from data-bs-* attributes
    switch (button.getAttribute('data-bs-action')) {
        case 'create':
            title.textContent = 'Créer une ruche'
            nameInput.value = '';
            latitudeInput.value = '';
            longitudeInput.value = '';

            submitButton.onclick = () => {
                const formData = new FormData();
                formData.append('name', nameInput.value)
                formData.append('longitude', longitudeInput.value)
                formData.append('latitude', latitudeInput.value)
                fetch('api/hive.php', {method: 'POST', body: formData})
                    .then(response => response.json())
                    .then(hive => {
                        $('#hives-list').DataTable().row.add(hive).draw(true);
                        document.getElementById('#navbarHiveCount').innerText++;
                        bootstrap.Modal.getInstance(hiveDetailModal).hide();
                    })
            }
            break;
        case 'edit':
            title.textContent = 'Modifier une ruche'
            nameInput.value = hive.name;
            longitudeInput.value = hive.longitude;
            latitudeInput.value = hive.latitude;

            submitButton.onclick = () => {
                const body = JSON.stringify({
                    id: hive.id,
                    name: nameInput.value,
                    longitude: longitudeInput.value,
                    latitude: latitudeInput.value
                });

                fetch('api/hive.php', {method: 'PUT', body: body})
                    .then(response => response.json())
                    .then(() => {
                            // Add the new row to prevent a full reload
                            $('#hives-list').DataTable().row(hive.row).data({
                                id: hive.id,
                                name: nameInput.value,
                                longitude: longitudeInput.value,
                                latitude: latitudeInput.value
                            }).draw(true);
                            // TODO: Toast
                            bootstrap.Modal.getInstance(hiveDetailModal).hide();
                    })
            }
            break;
    }
})

// noinspection ES6ConvertVarToLetConst
var hiveDeleteModal = document.getElementById('hive-delete');
hiveDeleteModal.addEventListener('show.bs.modal', function (event) {
    const body = hiveDeleteModal.querySelector('.modal-body>p')
    const deleteButton = hiveDeleteModal.querySelector('#hive-modal-delete')
    const hive = JSON.parse(decodeURIComponent(event.relatedTarget.getAttribute('data-bs-hive')));
    console.log(body)
    body.innerText = `Voulez-vous vraiment supprimer la ruche ${hive.name} ?`;
    deleteButton.onclick = () => {
        fetch('api/hive.php', {method: 'DELETE', body: JSON.stringify({id: hive.id})})
            .then(response => response.json())
            .then(successful => {
                if (successful) {
                    $('#hives-list').DataTable()
                        .row(hive.row)
                        .remove()
                        .draw(true);
                    // TODO: Toast
                    document.getElementById('#navbarHiveCount').innerText--;
                    bootstrap.Modal.getInstance(hiveDeleteModal).hide();
                } else {
                    // TODO: Inform of failure
                    // TODO: Toast
                }
            })
    }
});
