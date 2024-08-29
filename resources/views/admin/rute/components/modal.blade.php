<!-- Modal for Create and Edit -->
<div class="modal fade" id="customersModal" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Edit Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="ruteForm">
                    <input type="hidden" id="formRuteId" name="id">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <label for="formNamaLokasi" class="form-label">Nama Lokasi</label>
                                <input type="text" class="form-control" id="formNamaLokasi" name="nama_lokasi"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="formLatitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="formLatitude" name="latitude" required
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="formLongitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="formLongitude" name="longitude" required
                                    readonly>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-6">
                            <div id="mapEdit" style="height: 400px;"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveRuteBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Create -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLabel">Create Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create -->
                <form id="createRuteForm">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <label for="formCreateNamaLokasi" class="form-label">Nama Lokasi</label>
                                <input type="text" class="form-control" id="formCreateNamaLokasi" name="nama_lokasi"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="formCreateLatitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="formCreateLatitude" name="latitude"
                                    required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="formCreateLongitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="formCreateLongitude" name="longitude"
                                    required readonly>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-6">
                            <div id="mapCreate" style="height: 400px;"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createRuteBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let mapEdit;
        let markerEdit;

        let mapCreate;
        let markerCreate;

        // Function to initialize the map for the Edit Modal with given coordinates
        function setupEditMap(lat, lng) {
            // Initialize or reset the mapEdit
            if (!mapEdit) {
                mapEdit = L.map('mapEdit').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapEdit);
            } else {
                mapEdit.setView([lat, lng], 13);
            }

            // Initialize or reset the markerEdit
            if (markerEdit) {
                markerEdit.setLatLng([lat, lng]);
            } else {
                markerEdit = L.marker([lat, lng], {
                    draggable: true
                }).addTo(mapEdit);
                markerEdit.on('dragend', function(event) {
                    const position = event.target.getLatLng();
                    document.getElementById('formLatitude').value = position.lat;
                    document.getElementById('formLongitude').value = position.lng;
                });
            }

            // Handle map click to update marker position
            mapEdit.on('click', function(event) {
                const latLng = event.latlng;
                markerEdit.setLatLng(latLng);
                document.getElementById('formLatitude').value = latLng.lat;
                document.getElementById('formLongitude').value = latLng.lng;
            });
        }

        // Function to initialize the map for the Create Modal
        function setupCreateMap() {
            if (!mapCreate) {
                mapCreate = L.map('mapCreate').setView([-8.488706, 140.399272], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapCreate);
            }

            // Initialize or reset the markerCreate
            if (markerCreate) {
                mapCreate.removeLayer(markerCreate);
            }
            markerCreate = L.marker([-8.488706, 140.399272], {
                draggable: true
            }).addTo(mapCreate);
            markerCreate.on('dragend', function(event) {
                const position = event.target.getLatLng();
                document.getElementById('formCreateLatitude').value = position.lat;
                document.getElementById('formCreateLongitude').value = position.lng;
            });

            // Handle map click to update marker position
            mapCreate.on('click', function(event) {
                const latLng = event.latlng;
                markerCreate.setLatLng(latLng);
                document.getElementById('formCreateLatitude').value = latLng.lat;
                document.getElementById('formCreateLongitude').value = latLng.lng;
            });
        }

        // Event listener to initialize map with data when modal is shown
        document.getElementById('customersModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');

            // Fetch data from server and setup map
            $.ajax({
                type: 'GET',
                url: '/rute/edit/' + id,
                success: function(response) {
                    $('#customersModalLabel').text('Edit Location');
                    $('#formRuteId').val(response.id);
                    $('#formNamaLokasi').val(response.nama_lokasi);
                    $('#formLatitude').val(response.latitude);
                    $('#formLongitude').val(response.longitude);

                    // Setup the map with the response data
                    setupEditMap(response.latitude, response.longitude);
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });

        document.getElementById('create').addEventListener('show.bs.modal', function(event) {
            setupCreateMap();
        });

        // Handle Save button click event for Edit Modal
        document.getElementById('saveRuteBtn').addEventListener('click', function() {
            const lat = markerEdit.getLatLng().lat;
            const lng = markerEdit.getLatLng().lng;
            document.getElementById('formLatitude').value = lat;
            document.getElementById('formLongitude').value = lng;
            // Handle form submission or AJAX call here
        });

        // Handle Save button click event for Create Modal
        document.getElementById('createRuteBtn').addEventListener('click', function() {
            const lat = markerCreate.getLatLng().lat;
            const lng = markerCreate.getLatLng().lng;
            document.getElementById('formCreateLatitude').value = lat;
            document.getElementById('formCreateLongitude').value = lng;
            // Handle form submission or AJAX call here
        });
    </script>
@endpush
