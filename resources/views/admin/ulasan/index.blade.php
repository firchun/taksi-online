@extends('layouts.backend.admin')

@section('content')
    @if (Auth::user()->role == 'Supir')
        <div class="btn-group mb-3">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bx bx-home"></i>
                Home
            </a>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }}</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Refresh Data</span>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-ulasan" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Rating</th>
                                <th>Ulasan</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Rating</th>
                                <th>Ulasan</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-ulasan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('ulasan-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'bintang',
                        name: 'bintang'
                    },

                    {
                        data: 'ulasan',
                        name: 'ulasan'
                    },

                ]
            });
            $('.refresh').click(function() {
                $('#datatable-ulasan').DataTable().ajax.reload();
            });

        });
    </script>
@endpush
