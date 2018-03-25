@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">

    <!-- Datatables -->
    <link href="{{ asset('datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>Teachers<small> List of all teachers.</small></h2>
                <a href="{{ route('admin.teachers.create') }}" class='btn btn-primary btn-sm pull-right'><i class='fa fa-plus'></i> Create Teacher</a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name <small>(Click Name To Update)</small></th>
                            <th>Username</th>
                            <th>Subjects</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $index => $teacher)
                            <tr>
                                <td scope="row">{{ $index + 1 }}</td>
                                <td><a href="{{ route('user.update.index', ['user_id' => $teacher->id]) }}">{{ $teacher->name }}</a></td>
                                <td>{{ $teacher->username }}</td>
                                <td>
                                    @if($teacher->is_confirmed)
                                        <a class='btn btn-sm btn-default' href="{{ route('admin.teachers.subjects', ['teacher_id' => $teacher->id]) }} "><i class='fa fa-list'></i> View Subjects</a>
                                    @else
                                        <span class='label label-info'>N/A</span>
                                    @endif
                                </td>
                                <!-- <td colspan=2>
                                    @if($teacher->is_confirmed)
                                        <button data-id="{{ $teacher->id }}" data-confirm="0" type='submit' class='confirm btn btn-warning btn-sm' href="#"><i class='fa fa-mortar-board'></i> Un Confirm</button>
                                    @else
                                        <button data-id="{{ $teacher->id }}" data-confirm="1" type='submit' class='confirm btn btn-primary btn-sm' href="#"><i class='fa fa-save'></i> Confirm</button>
                                        <button data-id="{{ $teacher->id }}" type='button' class='delete-user btn btn-danger btn-sm' href="javascript:void(0)"><i class='fa fa-trash'></i> Delete</button>
                                    @endif
                                </td> -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection

@push('scripts')
    <!-- Example -->
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        $('button.confirm').on('click', function (e) {

            e.preventDefault();
            var student_id = $(this).attr('data-id');
            var is_confirm = $(this).attr('data-confirm');

            swal({
              title: "Are you sure?",
              text: "You can toggle confirmation later!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-danger',
              confirmButtonText: "Proceed Action",
              closeOnConfirm: true,
              closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post("/user/confirmation", {
                        _token : "{{ csrf_token() }}",
                        student_id : student_id,
                        is_confirm : is_confirm
                    }, function(response) {
                        window.location.reload();
                    });
                } else {
                    swal("", "Good job! Think before you click!", "success");
                }
            });
        });

        $('button.delete-user').on('click', function (e) {
            e.preventDefault();

            var teacher_id = $(this).attr('data-id');
            var is_confirm = $(this).attr('data-confirm');

            swal({
              title: "Are you sure?",
              text: "Set teacher to inactive!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-danger',
              confirmButtonText: "Proceed Action",
              closeOnConfirm: true,
              closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post("/user/delete/teacher", {
                        _token : "{{ csrf_token() }}",
                        teacher_id : teacher_id,
                    }, function(response) {
                        window.location.reload();
                    });
                } else {
                    swal("", "Good job! Think before you click!", "success");
                }
            });

        });
    </script>
    <script src="{{ asset('datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatables.net-buttons/js/dataTables.buttons.min.js') }}" ></script>
    <script src="{{ asset('datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
@endpush