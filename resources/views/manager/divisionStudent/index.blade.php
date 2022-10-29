@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12" >
            <div style="width:100%" class="card">
                <div class="card-header ">
                    <form id="form-filter">
                        <div class="form-group d-flex">
                            <div class="input-group mb-3 w-25 mr-5">
                                <h5 for="select-classe">Tên lớp học</h5>
                                <select class="custom-select select-filter-class" id="select-classe" name="classes" >
                                    <option selected>Chọn lớp học</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}"
                                            @if ((string)$class->id  === $selectedClass) selected @endif>
                                            {{$class->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <h5 style="margin-bottom:40px">Thông tin lớp học</h5>
                                <div id="class-info" >
                                    @isset($classInfo)
                                        Tên lớp: <span style="color:black" class="" >{{$classInfo->name}}</span> <br>
                                        Giáo viên: @isset($teacher->first_name)<span style="color:black" class="" > {{$teacher->first_name}} {{$teacher->last_name}}</span> @endisset <br>
                                        Loại lớp:
                                        @if($classInfo->class_mode === 1)
                                            <span style="color:black" class="" >Chính thức</span> <br>
                                        @endif
                                        @if($classInfo->class_mode !== 1)
                                            <span style="color:black" class="" >Cải thiện</span> <br>
                                        @endif
                                        Kiểu lớp:
                                        @if($classInfo->class_type=== 0)
                                            <span style="color:black" class="" >Lý thuyết</span> <br>
                                        @endif
                                        @if($classInfo->class_type=== 1)
                                            <span style="color:black" class="" >Thực hành</span> <br>
                                        @endif
                                        @if($classInfo->class_type=== 2)
                                            <span style="color:black" class="" >Lý thuyết + Thực hành</span> <br>
                                        @endif
                                        Thời gian:
                                        @if($classInfo->start_date !== null){
                                            <span style="color:black" class="" >{{$classInfo->start_date}} đến {{$classInfo->end_date}}</span> <br>
                                        @endif
                                    @endisset
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="input-group mb-3 w-15 mr-3">
                                <label for="select-course">Khóa</label>
                                <select class="custom-select select-filter-course" id="select-course" name="course" >
                                    <option selected>All...</option>
                                    @foreach($courses as $course )
                                        <option value="{{ $course->id }}"
                                                @if ((string)$course->id === $selectedCourse) selected @endif>
                                            {{$course->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-15 mr-3">
                                <label for="select-major">Ngành</label>
                                <select class="custom-select select-filter-major" id="select-major" name="major" >
                                    <option selected>All...</option>
                                    @foreach($majors as $major)
                                        <option value="{{ $major->id }}"
                                                @if ((string)$major->id === $selectedMajor) selected @endif>
                                            {{$major->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-15 mr-3">
                                <label for="select-student">Sinh viên</label>
                                <select class="custom-select select-filter-student" id="select-student" name="student" >
                                    <option selected>All...</option>
                                    @foreach($students as $student )
                                        <option value="{{ $student->first_name}} {{$student->last_name}}"
                                                @if ((string) ($student->first_name . " " . $student->last_name) === $selectedStudent) selected @endif>
                                            {{$student->first_name}} {{$student->last_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thông tin sinh viên</th>
                            <th>Thông tin học tập</th>
                            <th style="width:10%">Chọn</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $each)
                            <tr>
                                <td style="color:black">
                                    <a href="">
                                        {{$each->id}}
                                    </a>
                                </td>
                                <td style="">
                                    Name:
                                    <span style="color:black">{{$each->first_name}} {{$each->last_name}}</span>
                                    @if($each->gender === 1)
                                        <i style="font-Size:16px;color:blue" class="mdi mdi-gender-male"></i>
                                        <br>
                                    @endif
                                    @if($each->gender=== 2)
                                        <i style="font-Size:16px;color:hotpink" class="mdi mdi-gender-female"></i>
                                        <br>
                                    @endif
                                    @if($each->gender=== 3)
                                        <i style="font-Size:16px;color:green" class="mdi mdi-gender-male-female"></i>
                                        <br>
                                    @endif
                                    Ngày sinh:
                                    <span style="color:black">{{date("d/m/Y",strtotime($each->birthdate))}}</span>
                                    <br>
                                    <span >Mail:</span>
                                    <a href="mailto::{{$each->email}}">
                                        {{$each->email}}
                                    </a>
                                    <br>
                                    <span >Address:</span>
                                    <span style="color:black">{{$each->address}}</span>
                                </td>
                                <td>
                                    Khóa:
                                    <span style="color:black">{{$each->course_name}} </span>
                                    <br>
                                    Ngành:
                                    <span style="color:black">{{$each->major_name}} </span>
                                </td>
                                <td>
                                    <button class="btn btn-primary button-add-student-id" id="buttonSet{{$key}}"  value="{{$each->id}}" type="button">Thêm</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination pagination-rounded mb-0">
                            {{ $data->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
        <script>
            $('#select-course').select2();
            $('#select-major').select2();
            $('#select-student').select2();

            $('#select-classe').select2();
            $(document).ready(async function() {
                $('.select-filter-course, .select-filter-student,.select-filter-major,.select-filter-class').change(function(){
                     $('#form-filter').submit();
                });

                $('.button-add-student-id').click(function(e){
                    e.preventDefault();
                    let id = $(this).val();
                    let bId=$(this).attr('id');
                    console.log(bId);
                    $.ajax({
                        url: '{{ route('api.divisionStudent.set') }}',
                        type: 'POST',
                        data: {
                            class:$('#select-classe').val(),
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $('#'+bId).attr('class', 'btn btn-success button-add-student-id');
                            $('#'+bId).html('Đã thêm');
                        },
                        error: function(data) {
                            $('#'+bId).attr('class', 'btn btn-danger button-add-student-id');
                            $('#'+bId).html('thêm lại');
                            $.toast({
                                size: 'width-larger',
                                heading: 'Trùng lịch học với lớp:',
                                text: data.responseJSON.message,
                                showHideTransition: 'fade',
                                icon: 'error',
                                position: 'top-right',
                                hideAfter: 8000
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection()
