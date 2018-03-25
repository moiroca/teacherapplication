@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    	@if(!\Auth::user()->isAdmin())
        <div class="col-md-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                    <h2>Recent Announcements <small></small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <ul class="list-unstyled timeline widget">
                      <?php $now = \Carbon\Carbon::now(); ?>
                      @forelse($announcements as $announcement)
                        <li>
                          <div class="block">
                            <div class="block_content">
                              <h2 class="title"> <a>{{ $announcement->subject->name }} Announcement</a> </h2>
                              <div class="byline">
                                <?php $announcementDate = \Carbon\Carbon::parse($announcement->created_at); ?>
                                <?php $date = $now->diffForHumans($announcementDate); ?>
                                <span>{{ $date }}</span> by <a> {{ $announcement->subject->teacher->name }}</a>
                              </div>
                              <p class="excerpt">
                                {{ $announcement->content }}
                              </p>
                            </div>
                          </div>
                        </li>
                      @empty
                        <li>
                          <span class="label label-info">No Announcements Recorded Yet.</span>
                        </li>
                      @endforelse 
                    </ul>
                  </div>
                </div>
              </div>
            </div>
        </div>
      @else
        <div class="col-md-12">
            <p>Welcome <strong>{{ \Auth::user()->name }}!</strong></p>
        </div>
      @endif
    </div>
    <!-- /page content -->
@endsection