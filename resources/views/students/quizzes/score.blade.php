@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>Your {{ $quiz->title }} Quiz Score</h6> 
        <br/>
        <br/>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizItems as $index => $quizItem)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $quizItem->question }}</td>
                        <td>
                            @foreach($studentQuizAnswers as $quizAnswer)
                                @if($quizItem->id == $quizAnswer->quiz_item_id)
                                    @if($quizItem->correctOption()->id == $quizAnswer->quiz_option_id)
                                        <span class="label label-success">
                                            <?php $score += 1; ?>
                                            {{ $quizItem->correctOption()->content }}
                                        </span>
                                    @else
                                        <span class="label label-danger">
                                            {{ $quizItem->correctOption()->content }}
                                        </span>
                                    @endif
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">
                        <span class='pull-right'><strong>Your Score:</strong></span>
                    </td>
                    <td>
                        <span>{{ round(($score/$quizItems->count()) * 100) }}%</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection