@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush

@section('main_container')
    <!-- page content -->
    <div class="right_col" role="main">
      <div class="title_left">
            <h3>Teachers Subjects<small> list of teachers subject</small></h3>
      </div>
      <div class="clearfix"></div>
      <br/>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Total Student Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $index => $subject)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $subject->name }}</td>
                        <td>
                            {{ $subject->students->count() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Students Found!</strong> You can start adding student thru by enrollment in teachers enrollment page.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection