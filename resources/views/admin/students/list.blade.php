@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="title_left">
            <h3>Students<small> list of students</small></h3>
        </div>
        <div class="clearfix"></div>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    <tr>
                        <td scope="row">{{ $index + 1 }}</td>
                        <td><a href="{{ route('user.update.index', ['user_id' => $student->id]) }}">{{ $student->name }}</a></td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->is_confirmed)
                                <button data-id="{{ $student->id }}" data-confirm="0" type='submit' class='confirm btn btn-warning btn-sm' href="#"><i class='fa fa-mortar-board'></i> Un Confirm</button>
                            @else
                                <button data-id="{{ $student->id }}" data-confirm="1" type='submit' class='confirm btn btn-primary btn-sm' href="#"><i class='fa fa-save'></i> Confirm</button>
                                <button data-id="{{ $student->id }}" type='button' class='delete-user btn btn-danger btn-sm' href="#"><i class='fa fa-trash'></i> Delete</button>
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
@endpush