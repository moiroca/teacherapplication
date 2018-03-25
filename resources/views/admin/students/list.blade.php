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
                <h2>Students<small> List of all students.</small></h2>
                <form action="{{ route('admin.students') }}" class="form-inline pull-right">
                    <div class="input-group">
                        <input name="student_id" class="form-control" type="text" placeholder="Enter Student ID">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><i class='fa fa-search'></i></button>
                        </span>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                            <th>#</th>
                            <th>Name <small>(Click Name To Update)</small></th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($students as $index => $student)
                            <tr>
                                <td scope="row">{{ $index + 1 }}</td>
                                <td><a href="{{ route('user.update.index', ['user_id' => $student->id]) }}">{{ $student->name }}</a></td>
                                <td>{{ $student->username }}</td>
                                <td>
                                    @if($student->is_confirmed)
                                        <button data-id="{{ $student->id }}" data-confirm="0" type='submit' class='confirm btn btn-warning btn-sm' href="#"><i class='fa fa-mortar-board'></i> Un Confirm</button>
                                    @else
                                        <button data-id="{{ $student->id }}" data-confirm="1" type='submit' class='confirm btn btn-primary btn-sm' href="#"><i class='fa fa-save'></i> Confirm</button>
                                        <button data-id="{{ $student->id }}" type='button' class='delete-user btn btn-danger btn-sm' href="#"><i class='fa fa-trash'></i> Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <span class="pull-right">
                        {{ $students->appends(['student_id' => $student_id])->links() }}
                    </span>
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

            var student_id = $(this).attr('data-id');
            var is_confirm = $(this).attr('data-confirm');

            swal({
              title: "Are you sure?",
              text: "Set student to inactive!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-danger',
              confirmButtonText: "Proceed Action",
              closeOnConfirm: true,
              closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post("/user/delete/student", {
                        _token : "{{ csrf_token() }}",
                        student_id : student_id,
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