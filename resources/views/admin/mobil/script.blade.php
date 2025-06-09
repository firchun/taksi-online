@push('js')
    <script>
        $(function() {
            $('#datatable-mobil').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('mobil-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'supir',
                        name: 'supir'
                    },

                    {
                        data: 'mobil',
                        name: 'mobil'
                    },
                    {
                        data: 'status_mobil',
                        name: 'status_mobil'
                    }, {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-mobil').DataTable().ajax.reload();
            });

        });
    </script>
@endpush
