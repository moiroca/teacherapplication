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
              <h2>Subjects Quizzes and Exams <small> List of subjects with quizzes and exams </small></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Expiration</th>
                            <th>Attempts Taken</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $now = \Carbon\Carbon::now();
                        ?>
                        @foreach($activities as $index => $quiz)
                            <?php 
                                $quizExpiration = \Carbon\Carbon::parse($quiz->expiration);
                            ?>
                            <tr>
                                <td>
                                    <strong>{{ $quiz->title }} </strong> <small>Duration : {{ $quiz->duration }} {{ (1 == $quiz->time) ? 'Hour(s)' : 'Minute(s)' }}</small>
                                </td>
                                <td>
                                    @if($quizExpiration->lt($now))
                                        <label class='label label-danger'>Expired</label>
                                    @else
                                        Expires in : 
                                        <span class='label label-success'>
                                            {{ $quizExpiration->diffForHumans($now) }}
                                        </span>
                                    @endif
                                </td>
                                <td> 
                                    <span class='label label-info'>
                                    {{ $quiz->attempts }} out of {{ $quiz->total_attempt }} Attempts
                                    </span> 
                                </td>
                                <td>
                                    @if($quiz->attempts < $quiz->total_attempt && !$quizExpiration->lt($now))
                                        <a 
                                            class='btn btn-info btn-sm' 
                                            href="{{ route('students.quizzes.take', ['quiz_id' => $quiz->id]) }}">
                                                <i class='fa fa-bomb'></i> Take Exam/Quiz
                                        </a>
                                    @endif
                                    <a class='btn btn-default btn-sm' href="{{ route('students.quiz_atempts.result', ['quiz_id' => $quiz->id]) }}"><i class='fa fa-bar-chart'></i> View Result</a>
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
@endpush