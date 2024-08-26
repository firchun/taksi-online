<!-- Modal for Create and Edit -->
<div class="modal fade" id="customersModal" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="ruteForm">
                    <input type="hidden" id="formRuteId" name="id">
                    <div class="mb-3">
                        <label for="formNamaLokasi" class="form-label">Nama Lokasi</label>
                        <input type="text" class="form-control" id="formNamaLokasi" name="nama_lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="formLatitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="formLatitude" name="latitude" required>
                    </div>
                    <div class="mb-3">
                        <label for="formLongitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="formLongitude" name="longitude" required>
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
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createRuteForm">
                    <div class="mb-3">
                        <label for="formCreateNamaLokasi" class="form-label">Nama Lokasi</label>
                        <input type="text" class="form-control" id="formCreateNamaLokasi" name="nama_lokasi"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateLatitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="formCreateLatitude" name="latitude" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateLongitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="formCreateLongitude" name="longitude" required>
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
