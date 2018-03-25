@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
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
              <h2>Subjects List <small> List of subjects enrolled. </small></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if($errors->has('enrollment_key'))
                    <div class='alert alert-danger'>
                        <strong>Error!</strong> {{ $errors->first('enrollment_key') }}
                    </div>
                @endif
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Teacher</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->teacher->name }}</td>
                                <td>
                                    <button class='btn btn-info btn-sm enroll'><i class='fa fa-bar-chart'></i> Enroll Subject</button>
                                    <form method="POST" action="{{ route('enrollment.student.subject.save', ['subject_id' => $subject->id ]) }}" style="display: none;" class="form-inline">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="enrollment_key" placeholder="Enter Enrollment Key">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-info">
                                                    <i class='fa fa-save fa-lg'></i> Enroll
                                                </button>
                                            </span>
                                        </div>
                                    </form>
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
        $('button.enroll').on('click', function () {
            var btn = $(this);
            var form = btn.siblings('form');

            form.show();
            btn.hide();
        });

        $('.enroll-submit').on('click', function () {
            var submit_btn  = $(this);
            var form        = submit_btn.parents('form');

            form.submit();
        });
    </script>
@endpush