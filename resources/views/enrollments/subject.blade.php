@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush
@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>Subject: {{ $subject->name }}</h6>
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
                <input type="hidden" value="{{ $subject->id }}" id="subject_id" />
                @forelse($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->is_enrolled)
                                <form method="POST" action="{{ route('enrollment.subject.delete', ['subject_id' => $subject->id]) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button data-id="{{ $student->id }}" type='submit' class='un-enroll btn btn-warning btn-sm' href="#"><i class='fa fa-mortar-board'></i> Un-Enroll</button>
                               </form>
                                
                            @else
                               <form method="POST" action="{{ route('enrollment.subject.save', ['subject_id' => $subject->id]) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type='submit' class='btn btn-primary btn-sm' href="#"><i class='fa fa-save'></i> Enroll</button>
                               </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Student Registered!</strong> Ask your teacher to let studenst enroll in {{ config('app.name') }}.
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
        $('button.un-enroll').on('click', function (e) {

            e.preventDefault();
            var student_id = $(this).attr('data-id');

            swal({
              title: "Are you sure?",
              text: "You can enroll this student later!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-danger',
              confirmButtonText: 'Yes, Un-enroll student!',
              closeOnConfirm: true,
              closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var subject_id = $("#subject_id").val();

                    $.post("delete/" + subject_id, {
                        _token : "{{ csrf_token() }}",
                        student_id : student_id
                    }, function(response) {
                        window.location.reload();
                    });
                } else {
                    swal("", "You can un-enroll this student later.", "success");
                }
            });

        });
    </script>
@endpush