@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->

    <style type="text/css">
        .is-mistake {
            color: #f37878;    
            font-weight: bold;
        }
    </style>
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
                <h2>{{ $quiz->title }} Quiz Score<small> See how you perform</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Question</th>
                            @if(!\Auth::user()->isTeacher())
                                @if($quiz->allow_review)
                                    <th>Correct Answer</th>
                                    <th>Your Answer</th>
                                @endif
                            @else
                                <th>Correct Answer</th>
                                <th>Your Answer</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentQuizAnswers as $index => $quizItem)
                            <tr class="{{ ( $quizItem->is_correct ) ? 'is-correct' : 'is-mistake' }}">
                                <td>{{ $quizItem->question }}</td>
                                @if( $quizItem->is_correct )
                                    <?php $score += 1; ?>
                                @endif
                                @if(!\Auth::user()->isTeacher())
                                    @if($quiz->allow_review)
                                        <td>
                                            @if( $quizItem->is_correct )
                                                <span class="label label-success">
                                                    {{ $quizItem->content }}
                                                </span>
                                            @else
                                                <span class="label label-danger">
                                                    {{ $quizItem->content }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!is_null($quizItem->student_answer))
                                                {{  $quizItem->student_answer }}
                                            @else
                                                <span class='label label-warning'>Answer Not Provided</span>
                                            @endif
                                        </td>
                                    @endif
                                @else
                                    <td>
                                        @if( $quizItem->is_correct )
                                            <span class="label label-success">
                                                {{ $quizItem->content }}
                                            </span>
                                        @else
                                            <span class="label label-danger">
                                                {{ $quizItem->content }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($quizItem->student_answer))
                                            {{  $quizItem->student_answer }}
                                        @else
                                            <span class='label label-warning'>Answer Not Provided</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            @if(!\Auth::user()->isTeacher())
                                @if($quiz->allow_review)
                                    <td colspan="2">
                                        <span class='pull-right'><strong>Your Score:</strong></span>
                                    </td>
                                @endif
                                <td>
                                        @if($quiz->allow_review)
                                            {{ $studentQuizAnswers->count() ? round($score/$studentQuizAnswers->count() * 100) : 0 }}%
                                        @else
                                            Score Percentage: <strong> {{ $studentQuizAnswers->count() ? round($score/$studentQuizAnswers->count() * 100) : 0 }}% </strong>
                                        @endif
                                </td>
                            @else
                                    <td colspan="2">
                                        <span class='pull-right'><strong>Your Score:</strong></span>
                                    </td>
                                <td>
                                    {{ $studentQuizAnswers->count() ? round($score/$studentQuizAnswers->count() * 100) : 0 }}%
                                </td>
                            @endif
                        </tr>
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