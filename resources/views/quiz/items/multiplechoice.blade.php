@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h3>Create {{ $quiz->title }} Questions</h3>
        <br/>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <form method="post" action="{{ route('quiz.items.save', $quiz->id) }}" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                        Item <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea name="item" type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                    </div>
                </div>
                @for($i = 1; $i <= 4; $i++)
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            Option {{ $i }} <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="{{ 'options[' . $i . ']' }}" type="text" required="required" class="form-control col-md-7 col-xs-12">
                            <input name="answer" type="radio" value="{{ $i }}"> <small>Mark As Correct Answer</small>
                        </div>
                    </div>
                @endfor
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save Quiz</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quiz->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $item->question }} 
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
    <!-- /page content -->
@endsection