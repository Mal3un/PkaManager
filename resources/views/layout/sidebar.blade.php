<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <!-- LOGO -->
    <a href="{{route('manager.welcome')}}" class="logo text-center logo-light">
                    <span class="logo-lg">
                        <img src="{{asset('images/logo/logo.png')}}" alt="" height="64px">
                    </span>
        <span class="logo-sm">
                        <img src="{{asset('images/logo/logo_sm.png')}}" alt="" height="20">
                    </span>
    </a>

    <!-- LOGO -->
{{--    <a href="index.html" class="logo text-center logo-dark">--}}
{{--                    <span class="logo-lg">--}}
{{--                        <img src="assets/images/logo-dark.png" alt="" height="16">--}}
{{--                    </span>--}}
{{--        <span class="logo-sm">--}}
{{--                        <img src="assets/images/logo_sm_dark.png" alt="" height="16">--}}
{{--                    </span>--}}
{{--    </a>--}}

    <div class="h-100" id="left-side-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="metismenu side-nav">

            <li class="side-nav-title side-nav-item">Thông tin</li>
            @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 3)
                <li class="side-nav-item">
                    <a href="javascript: void(0);" class="side-nav-link">
                        <i class="mdi mdi-human-male-female"></i>
                        <span> Sinh viên </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                        @if(Auth::user()->role_id === 3)
                            <li>
                                <a href="{{route('manager.students.index')}}">Thông tin sinh viên</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{route('manager.students.schedule')}}">Xem lịch học của sinh viên</a>
                        </li>
                            <li>
                                <a href="{{route('manager.scores.index')}}">Xem điểm của sinh viên</a>
                            </li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->role_id === 2 || Auth::user()->role_id === 3)
                <li class="side-nav-item">
                    <a href="javascript: void(0);" class="side-nav-link">
                        <i class="uil-users-alt"></i>
                        <span> Giáo viên </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                        @if(Auth::user()->role_id === 3)
                            <li>
                                <a href="{{route('manager.teachers.index')}}">Danh sách giáo viên</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{route('manager.division.index')}}">Danh sách phần công</a>
                        </li>
                        <li>
                            <a href="{{route('manager.teachers.schedule')}}">Xem lịch dậy của giáo viên</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="side-nav-title side-nav-item">Quản lý</li>
            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="mdi mdi-google-classroom"></i>
                    <span> Lớp học </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">

                    <li>
                        <a href="{{route('manager.classes.index')}}">{{auth()->user()->role_id !== 3 ? 'Lớp học của tôi' : 'Danh sách lớp học'}}</a>
                    </li>
                </ul>
            </li>
            @if(auth()->user()->role_id ===3)
                <li class="side-nav-item">
                    <a href="javascript: void(0);" class="side-nav-link">
                        <i class=" uil-calendar-alt"></i>
                        <span> Phân công dậy | học</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                        <li>
                            <a href="{{route('manager.division.index2')}}">Giáo viên</a>
                        </li>
                        <li>
                            <a href="{{route('manager.divisionstudent.index')}}">Sinh viên</a>
                        </li>
                    </ul>
                </li>
                <li class="side-nav-item">
                    <a href="{{route('manager.scores.index')}}" class="side-nav-link">
                        <i class="uil-pen"></i>
                        <span> Nhập điểm cuối kì </span>
                    </a>
                </li>
            @endif
            <li class="side-nav-item">
                <a href="{{route('manager.subjects.index')}}" class="side-nav-link">
                    <i class="mdi mdi-book-open-page-variant"></i>
                    <span> Môn học </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{route('manager.courses.index')}}" class="side-nav-link">
                    <i class="uil-graduation-hat"></i>
                    <span> Khóa học </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class=" uil-chart-line"></i>
                    <span> Chương trình đào tạo </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                    <li>
                        <a href="{{route('manager.majors.index')}}">Chương trình chính thức</a>
                    </li>
                    <li>
                        <a href="apps-ecommerce-products-details.html">Chương trình phụ</a>
                    </li>
                    <li>
                        <a href="apps-ecommerce-orders.html">Khác</a>
                    </li>
                </ul>
            </li>
            <li class="side-nav-title side-nav-item">Chức năng khác</li>
            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class=" uil-monitor-heart-rate"></i>
                    <span> Thi học phần </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                    <li>
                        @if(auth()->user()->role_id === 3)
                            <a href="{{route('manager.exams.index')}}">Tạo lịch thi</a>
                        @else
                            <a href="{{route('manager.exams.index')}}">Xem lịch thi</a>
                        @endif
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-newspaper"></i>
                    <span> Tin tức </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                    <li>
                        @if(auth()->user()->role_id === 3)
                            <a href="{{route('manager.posts.index')}}">Tạo tin tức</a>
                        @else
                            <a href="{{route('manager.posts.index')}}">Tất cả tin tức</a>
                        @endif
                    </li>
                </ul>
            </li>
            @if(auth()->user()->role_id === 3)
                <li class="side-nav-item">
                    <a href="javascript: void(0);" class="side-nav-link">
                        <i class="uil-chart"></i>
                        <span> Thống kê </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="side-nav-second-level mm-collapse " aria-expanded="false" style="">
                        <li>
                            <a href="{{route('manager.charts.index')}}">Xem thống kê điểm số</a>
{{--                               <a href="{{route('manager.posts.index')}}">Xem thông kê học tập</a>--}}
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
