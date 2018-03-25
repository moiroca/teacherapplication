<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title"><img src="{{ asset('aclc-logo/45x45.png') }}" alt="ACLC"> <span>{{ config('app.initial') }}</span></a>
        </div>
        
        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <!-- <div class="profile">
            <div class="profile_pic">
                <img src="{{ Gravatar::src(Auth::user()->email) }}" alt="Avatar of {{ Auth::user()->name }}" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name }}</h2>
            </div>
        </div> -->
        <!-- /menu profile quick info -->
        <br />
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Information Management</h3>
                @if(Auth::user()->isTeacher())
                    <ul class="nav side-menu">
                        <li>
                            <a href="{{ route('announcements.index') }}">
                                <i class="fa fa-bullhorn"></i>
                                Announcements
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('modules.index') }}">
                                <i class="fa fa-file"></i>
                                Modules
                            </a>
                        </li>
                        <li><a><i class="fa fa-file-text"></i> Exam Management <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{ route('quiz.index') }}">Quizzes</a></li>
                                <li><a href="{{ route('exams.index') }}">Exams</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('subject.index') }}">
                                <i class="fa fa-flask"></i>
                                Subject Management
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('quizzes.subjects') }}">
                                <i class="fa fa-flask"></i>
                                Quiz Output
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('exams.subjects') }}">
                                <i class="fa fa-flask"></i>
                                Exam Output
                            </a>
                        </li>
                    </ul>
                @elseif(Auth::user()->isAdmin())
                    <ul class="nav side-menu">
                        <li>
                            <a href="{{ route('admin.teachers') }}">
                                <i class="fa fa-flask"></i>
                                Teachers Management
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.students') }}">
                                <i class="fa fa-flask"></i>
                                Students Management
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="nav side-menu">
                        <li>
                            <a href="{{ route('dashboard') }}">
                                <i class="fa fa-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('modules.students.index') }}">
                                <i class="fa fa-file"></i>
                                Modules
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('students.subjects') }}">
                                <i class="fa fa-flask"></i>
                                Enrolled Subjects
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('students.subjects.list') }}">
                                <i class="fa fa-flask"></i>
                                View All Subjects
                            </a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>