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
                <h2>Teachers Subjects<small> List of all subjects.</small></h2> 
                <a  class="btn btn-primary btn-sm pull-right" href="{{ route('subject.create') }}"><i class='fa fa-plus'></i> Create Subject</a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <td>Enrollment Key</td>
                            <td>School Year</td>
                            <td>Semester</td>
                            <th>Total Student Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                            <tr>
                                <td>
                                    <a href="{{ route('subject.students', $subject->id) }}">{{ $subject->name }}</a>
                                </td>
                                <td>{{ $subject->enrollment_key }}</td>
                                <td>
                                    {{ $subject->schoolyear->from }} - {{ $subject->schoolyear->to }}
                                </td>
                                <td>
                                    {{ config('app.semesters')[$subject->semester] }}
                                </td>
                                <td>
                                    <?php $studentCount = $subject->students->count(); ?>
                                    @if($studentCount <= 0)
                                        <span class="label label-warning">Empty</span>
                                    @else
                                        <span class='label label-info'>{{ $studentCount }}</span>
                                    @endif
                                    
                                </td>
                                <td>
                                    <a class='btn btn-default btn-sm' href="{{ route('modules.subject.index', ['subject_id' => $subject->id]) }}"> 
                                        <i class='fa fa-bullhorn'></i> View Announcements
                                    </a> 
                                    <a data-id="{{ $subject->id }}" class="delete-subject btn btn-warning btn-sm" href="javascript:void(0)">
                                      <i class='fa fa-trash'></i> Delete Subject
                                    </a>
                                </td>
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