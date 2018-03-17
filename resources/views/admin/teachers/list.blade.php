@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="title_left">
            <h3>Teachers<small> list of teachers</small></h3>
        </div>
        <div class="clearfix"></div>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subjects</th>
                    <th colspan=2>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $index => $teacher)
                    <tr>
                        <td scope="row">{{ $index + 1 }}</td>
                        <td><a href="{{ route('user.update.index', ['user_id' => $teacher->id]) }}">{{ $teacher->name }}</a></td>
                        <td>{{ $teacher->email }}</td>
                        <td>
                            @if($teacher->is_confirmed)
                                <a href="{{ route('admin.teachers.subjects', ['teacher_id' => $teacher->id]) }} "><i class='fa fa-list'></i> View Subjects</a>
                            @else
                                <span class='label label-info'>N/A</span>
                            @endif
                        </td>
                        <td colspan=2>
                            @if($teacher->is_confirmed)
                                <button data-id="{{ $teacher->id }}" data-confirm="0" type='submit' class='confirm btn btn-warning btn-sm' href="#"><i class='fa fa-mortar-board'></i> Un Confirm</button>
                            @else
                                <button data-id="{{ $teacher->id }}" data-confirm="1" type='submit' class='confirm btn btn-primary btn-sm' href="#"><i class='fa fa-save'></i> Confirm</button>
                                <button data-id="{{ $teacher->id }}" type='button' class='delete-user btn btn-danger btn-sm' href="javascript:void(0)"><i class='fa fa-trash'></i> Delete</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Students Registered!</strong> You can start adding student thru registration.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
@endpush