@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
    <style type="text/css">
        span.item {
            cursor: pointer;
            font-weight: bold;
        }
        span.item:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    @if($quiz->isDraft())
                        <h2>Create {{ $quiz->title }} Questions <small>Save questions for this quiz.</small></h2>
                    @else
                        <h2>{{ $quiz->title }} Questions <small>List of questions for this quiz.</small></h2>
                    @endif
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($quiz->isDraft())
                        <div class="col-md-6">
                            <form method="post" action="{{ route('quiz.items.save', $quiz->id) }}" data-parsley-validate class="form-label-left">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group">
                                    <label class="control-label col-md-12 col-xs-12" for="question">
                                        Item <span class="required">*</span>
                                    </label>
                                    <div class="col-md-12 col-sm-12">
                                        <textarea name="item" type="text" id="question" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12 col-sm-3 col-xs-12" for="answer">
                                        Answer <span class="required">*</span>
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input name="answer" type="text" id="answer" required="required" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <br/>
                                        <button id="save-question" type="submit" class="btn btn-success btn-sm">
                                            <i class='fa fa-save'></i> Save Question</button>
                                        <button style="display: none;" id="save-as-draft" type="button" class="btn btn-default btn-sm"><i class='fa fa-save'></i> Update Question</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="{{ ($quiz->isPublished()) ? 'col-md-12' : 'col-md-6' }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        @if($quiz->isDraft())
                                            ITEM <small>(Click answer to edit)</small>
                                        @else
                                            <strong>{{ $quiz->title }} </strong>questions <small>(Ready for students to take.) </small>
                                        @endif
                                    </th>
                                    <th>
                                        @if($quiz->isDraft() && 0 != $quiz->items->count())
                                            <form method="POST" action="{{ route('activity.publish') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="activity_id" value="{{ $quiz->id }}">
                                                <button id="publish-quiz" type="submit" class="btn btn-primary pull-right btn-sm"><i class='fa fa-save'></i> Publish Quiz</button>
                                            </form>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                                @forelse($quiz->items as $index => $item)
                                    <tr>
                                        <td colspan="3">
                                            {{ $item->question }} 
                                            @if($item->quiz_item_type == 1)
                                                @foreach($item->options as $option)
                                                    <span 
                                                        data-option-id="{{ $option->id }}"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item="{{ $item->question }}"
                                                        data-answer="{{ $option->content }}"
                                                        class="label label-success {{ ($quiz->isDraft()) ? 'item' : '' }}"
                                                        >
                                                        {{ $option->content }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <ol>
                                                    @foreach($item->options as $option)
                                                        <li>
                                                            @if($option->is_correct)
                                                                {{ $option->content }}  <span class="label label-success">Correct Answer</span>
                                                            @else
                                                                {{ $option->content }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">
                                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Quiz has no questions yet!</strong> You can add questions on the right side.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            onLoad();

            $('span.item').on('click', function () {
                var answer = $(this).attr('data-answer');
                var question = $(this).attr('data-item');
                var option_id = $(this).attr('data-option-id');
                var item_id  = $(this).attr('data-item-id');

                $('#question').val(question);
                $('#answer').val(answer);

                // Show Save As Draft Button
                var save_as_draft_btn   = $('#save-as-draft');
                var save_question_btn   = $('#save-question');

                save_question_btn.html("<i class='fa fa-save'></i> Save As New Question");

                save_as_draft_btn.attr('data-item-id', item_id);
                save_as_draft_btn.attr('data-option-id', option_id);
                save_as_draft_btn.show();
            });

            $('#save-as-draft').on('click', function (e) {
                e.preventDefault();

                var quiz_id     = $('input[name="quiz_id"]').val();
                var option_id   = $(this).attr('data-option-id');
                var item_id     = $(this).attr('data-item-id');

                $.post("/quiz/update", {
                        _token      : "{{ csrf_token() }}",
                        quiz_id     : quiz_id,
                        option_id   : option_id,
                        item_id     : item_id,
                        question    : $('#question').val(),
                        answer      : $('#answer').val()
                    }, function(response) {
                        window.location.reload();
                    });
            });

            function onLoad() {
                $('#question').val('');
                $('#answer').val('');
            }
        });
    </script>
@endpush