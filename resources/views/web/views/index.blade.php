@extends('web.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link rel="stylesheet" href="{{ asset('web/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
@endpush

@section('content')
    @include('web.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">{{ strtoupper($ref['title']) }}</h6>
                            <a href="{{ route('project-monitoring.create') }}" class="btn bg-gradient-primary float-end">Tambah Data</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-stripe categories_table">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>PROJECT NAME</th>
                                    <th>CLIENT</th>
                                    <th>PROJECT LEADER</th>
                                    <th>START DATE</th>
                                    <th>END DATE</th>
                                    <th>PROGRESS</th>
                                    <th class="text-center w-1">ACTION</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_script')
    <script src="{{ asset('web/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('web/js/dataTables.bootstrap5.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('.categories_table').DataTable({
                language: {
                    paginate: {
                        next: "›",
                        previous: "‹"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('project-monitoring.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'project_name',
                        name: 'project_name'
                    },
                    {
                        data: 'client',
                        name: 'client'
                    },
                    {
                        data: 'project_leader',
                        name: 'project_leader',
                        searchable: false
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'progress',
                        name: 'progress',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                        "targets": 0,
                        "className": "text-center align-middle text-sm font-weight-normal",
                        "width": "4%"
                    },
                    {
                        "targets": 1,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 2,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 3,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 4,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 5,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 6,
                        "className": "align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 7,
                        "className": "align-middle text-sm font-weight-normal",
                    }
                ]
            });

            $(document).on('click', '#deleteRow', function(event) {
                var form = $(this).closest("form");
                var name = $(this).data("name");
                console.log($('.categories_table tr.active'));
                event.preventDefault();
                $.confirm({
                    icon: 'fa fa-warning',
                    title: 'Yakin Hapus Data',
                    content: 'Data ' + $(this).data('message') + ' Akan di hapus secara permanen',
                    type: 'orange',
                    typeAnimated: true,
                    animationSpeed: 500,
                    closeAnimation: 'zoom',
                    closeIcon: true,
                    closeIconClass: 'fa fa-close',
                    draggable: true,
                    backgroundDismiss: false,
                    backgroundDismissAnimation: 'glow',
                    buttons: {
                        delete: {
                            text: 'Hapus',
                            btnClass: 'btn-red',
                            action: function() {
                                form.submit();
                                // $.ajax({
                                //     type: 'POST',
                                //     dataType: 'json',
                                //     timeout: 15000
                                // }).done(function(msg, status) {
                                //     //remove data from list
                                // }).fail(function() {
                                //     let res = msg.responseJSON;
                                //     console.log(msg);
                                // })
                            }
                        },
                        batal: function() {}
                    }
                });
            });
        });
    </script>
@endpush

        