@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
    <style type="text/css">
        .quiz_item {
            font-weight: bold;
        }

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
        @if($quiz->isDraft())
            <h3>Create {{ $quiz->title }} Questions</h3>
        @endif
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div class="col-md-6">
                @if($quiz->isDraft())
                    <form method="post" action="{{ route('quiz.items.save', $quiz->id) }}" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                Item <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea name="item" type="text" id="question" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                            </div>
                        </div>
                        @for($i = 1; $i <= 4; $i++)
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                    Option {{ $i }} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input name="{{ 'options[' . $i . ']' }}" type="text" required="required" class="form-control col-md-7 col-xs-12 item-option-input">
                                    <input name="answer" type="radio" value="{{ $i }}"> <small>Mark As Correct Answer</small>
                                </div>
                            </div>
                        @endfor
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-6 col-xs-12 col-md-offset-3">
                                <button id="save-question" type="submit" class="btn btn-success btn-sm">
                                    <i class='fa fa-save'></i> Save Question
                                </button>
                                <button style="display: none;" id="save-as-draft" type="button" class="btn btn-default btn-sm"><i class='fa fa-save'></i> Update Question</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            <div class="{{ ($quiz->isPublished()) ? 'col-md-12' : 'col-md-6' }}">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                @if($quiz->isDraft())
                                    ITEM <small>(Click qustion to edit)</small>
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
                        @forelse($quiz->items as $index => $item)
                            <tr>
                                <td colspan="2">
                                    <span 
                                        data-item-id="{{ $item->id }}" class="{{ ($quiz->isDraft()) ? 'item' : 'quiz_item' }}" data-question="{{ $item->question }}">{{ $item->question }} </span>
                                    <ol>
                                        @foreach($item->options as $option)
                                            <li 
                                                data-option-id="{{ $option->id }}" 
                                                data-correct="{{ $option->is_correct }}" 
                                                class="item-option" 
                                                data-content="{{ $option->content }}">
                                                @if($option->is_correct)
                                                    {{ $option->content }}  <span class="label label-success">Correct Answer</span>
                                                @else
                                                    {{ $option->content }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
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
    <!-- /page content -->
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            onLoad();

            $('span.item').on('click', function () {
                var options = $(this).siblings('ol').find('li.item-option');
                var item_id  = $(this).attr('data-item-id');
                var item_option_input = $('.item-option-input');

                // Show Save As Draft Button
                var save_as_draft_btn   = $('#save-as-draft');
                var save_question_btn   = $('#save-question');


                // Set Question In Textarea
                $('textarea[name="item"]').val($(this).attr('data-question'));

                for (var i = 0; i < options.length; i++) {
                    var input = $(item_option_input[i]);
                    input.val($(options[i]).attr('data-content'));
                    input.attr('data-option-id', $(options[i]).attr('data-option-id'));

                    // Check Radio Button If Option  is Marked as Correct
                    if (1 == $(options[i]).attr('data-correct')) {
                        $(input).siblings('input[type="radio"]').prop('checked', true)
                    }
                }

                save_question_btn.html("<i class='fa fa-save'></i> Save As New Question");
                save_as_draft_btn.attr('data-item-id', item_id);
                save_as_draft_btn.show();
            });

            $('#save-as-draft').on('click', function (e) {
                e.preventDefault();

                var item_option_input   = $('.item-option-input');
                var item_id             = $(this).attr('data-item-id');

                var options_content = [];

                for (var i = 0; i < item_option_input.length; i++) {
                    options_content.push({ 
                        'value' : $(item_option_input[i]).val(),
                        'id'    : $(item_option_input[i]).attr('data-option-id')
                    });

                }

                $.post("/quiz/update/multiple-choice", {
                        _token      : "{{ csrf_token() }}",
                        options_content : options_content,
                        item_id     : item_id,
                        question    : $('#question').val(),
                        answer      : $('input[name="answer"]:checked').val()
                    }, function(response) {
                        window.location.reload();
                    });
            });

            function onLoad() {
                $('textarea[name="item"]').val('');

                var item_option_input = $('.item-option-input');

                for (var i = 0; i < item_option_input.length; i++) {
                    var input = $(item_option_input[i]);
                    
                    input.val('');
                    $(input).siblings('input[type="radio"]').prop('checked', false);
                }
            }
        });
    </script>
@endpush