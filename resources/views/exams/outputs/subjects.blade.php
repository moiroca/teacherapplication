@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
      <div class="title_left">
          <h3> Subjects With Exams <small> List of subjects with exams </small></h3>
      </div>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $index => $subject)
                    <tr>
                        <td scope="row">{{ $index + 1 }}</td>
                        <td><a href="{{ route('exams.subjects.exam_list', ['subject_id' => $subject->id]) }}">{{ $subject->name }}</a></td>
                        <td>
                            <a href="{{ route('exams.subjects.exam_list', ['subject_id' => $subject->id])  }}"> 
                                <i class='fa fa-bullhorn'></i> View Exams
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                         <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Exams in this Subject!</strong> Create one by visiting Exam Management Page.
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
        var isSubmitted = false;

        $('.delete-subject').on('click', function (e) {
            
            e.preventDefault();
            var subject_id = $(this).attr('data-id');

            swal({
              title: "Are you sure?",
              text: "You will not be able to recover this subject!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-danger',
              confirmButtonText: 'Yes, delete it!',
              closeOnConfirm: true,
              closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post("subjects/" + subject_id,{
                        _token : "{{ csrf_token() }}"
                    }, function(response) {
                        console.log(response.has_dependency);
                        if (response.has_dependency) {
                            swal({
                              title: "There are dependencies. Are you sure?",
                              text: "Once deleted, you will not be able to recover this dependency. " + response.msg,
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonClass: 'btn-danger',
                              confirmButtonText: 'Yes, delete it!',
                              closeOnConfirm: true,
                              closeOnCancel: false,
                            }, function(willDelete) {
                                if (willDelete) {
                                    $.post("subjects/" + subject_id + '?is_force=true',{
                                        _token : "{{ csrf_token() }}"
                                    }, function(response) {
                                        window.location.reload();
                                    });
                                  } else {
                                    swal("", "Good job! Think before you delete!", "success");
                                  }
                            });
                        } else {
                            window.location.reload();
                        }
                    });
                }
            });

        });
    </script>
@endpush