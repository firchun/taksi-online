@push('js')
    <script>
        $(function() {
            $('#datatable-rute').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('rute-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'nama_lokasi',
                        name: 'nama_lokasi'
                    },
                    {
                        data: 'mobil',
                        name: 'mobil'
                    },

                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.create-new').click(function() {
                $('#create').modal('show');
            });
            $('.refresh').click(function() {
                $('#datatable-rute').DataTable().ajax.reload();
            });
            window.editRute = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/rute/edit/' + id,
                    success: function(response) {
                        $('#customersModalLabel').text('Edit Customer');
                        $('#formRuteId').val(response.id);
                        $('#formNamaLokasi').val(response.nama_lokasi);
                        $('#formLatitude').val(response.latitude);
                        $('#formLongitude').val(response.longitude);
                        $('#customersModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveRuteBtn').click(function() {
                var formData = $('#ruteForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/rute/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-rute').DataTable().ajax.reload();
                        $('#customersModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createRuteBtn').click(function() {
                var formData = $('#createRuteForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/rute/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#formCreateNamaLokasi').val('');
                        $('#formCreateLatitude').val('');
                        $('#formCreateLongitude').val('');
                        $('#datatable-rute').DataTable().ajax.reload();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteRute = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus rute ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/rute/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-rute').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };
        });
    </script>
@endpush
