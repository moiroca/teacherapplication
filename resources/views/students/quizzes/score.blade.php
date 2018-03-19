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
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="title_left">
            <h3>{{ $quiz->title }} Quiz Score<small> See how you perform</small></h3>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Correct Answer</th>
                    <th>Your Answer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentQuizAnswers as $index => $quizItem)
                    <tr class="{{ ( $quizItem->is_correct ) ? 'is-correct' : 'is-mistake' }}">
                        <td>{{ $quizItem->question }}</td>
                        <td>
                            @if( $quizItem->is_correct )
                                <span class="label label-success">
                                    <?php $score += 1; ?>
                                    {{ $quizItem->content }}
                                </span>
                            @else
                                <span class="label label-danger">
                                    {{ $quizItem->content }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $quizItem->student_answer }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">
                        <span class='pull-right'><strong>Your Score:</strong></span>
                    </td>
                    <td>
                        <span>{{ round($score/$studentQuizAnswers->count() * 100) }}%</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection