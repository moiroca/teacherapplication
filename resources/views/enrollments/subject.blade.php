@extends('layouts.blank')

@push('stylesheets')
    <!-- Sweet Alert -->
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
                <h2>{{ $subject->name }}<small>Enroll students for this subject.</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                @foreach($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <input type="hidden" id="subject_id" name="subject_id" value="{{ $subject->id }}">
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