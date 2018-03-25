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
              <h2> {{ $subject->name }} Exams <small> List of Exams </small></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Items</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $index => $exam)
                            <tr>
                                <td>
                                    <a href="{{ route('quiz.items.create', $exam->id) }}">{{ $exam->title }}</a>
                                </td>
                                <td>{{ $exam->exam_item_count }}</td>
                                <td>
                                    <a class='btn btn-info btn-sm' href="{{ route('exams.subjects.exam_list.result', ['subject_id' => $exam->subject_id, 'exam_id' => $exam->id]) }}"><i class='fa fa-bullhorn'></i> View Result</a>
                                    @if(!$exam->allow_review)
                                        <button data-id="{{ $exam->id }}" class='btn btn-sm btn-success allow-review'><i class='fa fa-eye'></i> Allow Review</button>
                                    @else
                                        <button data-id="{{ $exam->id }}" class='btn btn-sm btn-warning allow-review'><i class='fa fa-eye'></i> Disable Review</button>
                                    @endif
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
        $('button.allow-review').on('click', function () {
            var btn = $(this);

            $.post({
                url : "{{ route('quizzes.allow_review') }}",
                data : {
                    _token : "{{ csrf_token() }}",
                    quiz_id : $(btn).attr('data-id')
                }
            }, function (response) {
                window.location.reload();
            });
        });
    </script>
@endpush