@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Take {{ $quiz->title }} Quiz <small > Remaining : <span id='duration'> 00:00:00 </span></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                     <form action="{{ route('students.quizzes.answer', ['quiz_id' => $quiz->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="student_quiz_id" value="{{ $studentQuiz->id }}">
                        <input type="hidden" name="quiz_item_id" value="{{ $currentQuizItem->id }}">
                        <label for="answer">{{ $currentQuizItem->question }}</label></p>
                        @if($currentQuizItem->quiz_item_type == \App\Models\QuizItem::IDENTIFICATION)
                            <input type='text' id="answer" name='answer' class="form-control" required>
                        @else
                            <ul >
                                @foreach($currentQuizItem->options as $index => $option)
                                    <li>
                                        <input type="radio" name="quiz_option_id" value="{{ $option->id }}"> {{ $option->content}}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <br/>
                        <button type='submit' class="btn btn-primary btn-sm"><i class='fa fa-save'></i> Save Answer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        <?php 
            $now = \Carbon\Carbon::now();
            $quizEndDateTime = \Carbon\Carbon::parse($studentQuiz->end_datetime);
            $secondsRemaining = $quizEndDateTime->diffInSeconds($now);
        ?>
          // Set the date we're counting down to
          var lastSeconds = "{{ $secondsRemaining }}";
          var is_expired = false;
          // var countDownDateTime = countDownDate.getTime();

          // Update the count down every 1 second
          var x = setInterval(function() {

            // Time calculations for days, hours, minutes and seconds
            var hours = Math.floor((lastSeconds / (60 * 60)));
            var minutes = Math.floor((lastSeconds / 60) % 60);
            var seconds = Math.floor(lastSeconds % 60);

            // If the count down is finished, write some text
            if (hours < 0) {
                swal({
                    title : 'Time is up!',
                    text  : "Your time is over. You will be redirected to your score page.",
                    type  : "success",
                    confirmButtonText: 'Yes, show me my score!',
                }, function () {
                    $.post({
                        url : '/force-submit',
                        data : {
                            student_quiz_id : $("input[name='student_quiz_id']").val(),
                            _token : "{{ csrf_token() }}",
                        }
                    }, function (response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        }
                    });
                });
                
                clearInterval(x); 
                return;
            } 

            // Display the result in the element with id="demo"
            document.getElementById("duration").innerHTML = hours + " hours "
            + minutes + " minutes " + seconds + ' seconds';

            lastSeconds -= 1;
          }, 1000);
  </script>
@endpush