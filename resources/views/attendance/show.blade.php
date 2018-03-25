@extends('layouts.blank')

@push('stylesheets')
    <!-- Datatables -->
    <link href="{{ asset('datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .status {
            cursor: pointer;
        }
    </style>
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{ date_create($attendance->date)->format('D, M j Y') }} Announcements <small> view attendance.</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status <small>(Toggle To Mark As Absent/Present)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjectStudents as $index => $subjectStudent)
                            <tr>
                                <td>{{ $subjectStudent->student->name }}</td>
                                <td>{{ $subjectStudent->student->email }}</td>
                                <td>
                                    @if(in_array($subjectStudent->student->id, $studentAttendance))
                                        <span data-student-id="{{ $subjectStudent->student->id }}" data-value="2" class='status label label-success'>PRESENT</span>
                                    @else
                                        <span data-student-id="{{ $subjectStudent->student->id }}" data-value="1" class='status label label-warning'>ABSENT</span>
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
    <script type="text/javascript">
        $('span.status').on('click', function () {
            $.post({
                method : "POST",
                url : "{{ route('attendance.toggle', ['attendance_id' => $attendance->id]) }}",
                data : {
                    value : $(this).attr('data-value'),
                    student_id : $(this).attr('data-student-id'),
                    _token : "{{ csrf_token() }}"
                }
            }, function (response) {
                window.location.reload();
            });
        });
    </script>
@endpush