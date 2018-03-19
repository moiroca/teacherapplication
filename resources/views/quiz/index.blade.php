@extends('layouts.blank')

@push('stylesheets')
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
                <h2>Teacher Quizzes<small> List of quizzes.</small></h2>
                <a  class="btn btn-primary btn-sm pull-right" href="{{ route('quiz.create') }}"><i class='fa fa-plus'></i> Create Quiz</a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzes as $index => $quiz)
                            <tr>
                                <td>
                                    <a href="{{ route('quiz.items.create', $quiz->id) }}">{{ $quiz->title }}
                                    </a>
                                </td>
                                <td>
                                    @if($quiz->isDraft())
                                        <span class="label label-warning">
                                            Draft
                                        </span>
                                    @else
                                        <span class="label label-success">
                                            Published
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $quiz->subject->name }}</td>
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
@endpush