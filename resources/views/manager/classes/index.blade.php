@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header " >
                    <form id="form-filter">
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
                                <label for="select-subject">Môn học</label>
                                <select class="custom-select select-filter-subject" id="select-subject" name="subject" >
                                    <option selected>All...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                                @if ((string)$subject->id === $selectedSubject) selected @endif>
                                            {{$subject->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-15 mr-3">
                                <label for="select-class-type">Loại lớp</label>
                                <select class="custom-select select-filter-class-type" id="select-class-type" name="classType" >
                                    <option selected>All...</option>
                                    @foreach($class_types as $class_type => $value)
                                        <option value="{{ $value }}"
                                                @if ((string)$value === $selectedClassType) selected @endif>
                                            {{$class_type}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(auth()->user()->role_id === 3)
                                <div class="input-group mb-3 w-15 mr-3">
                                    <label for="select-teacher">Giáo viên</label>
                                    <select class="custom-select select-filter-teacher" id="select-teacher" name="teacher" >
                                        <option selected>All...</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                    @if ((string)$teacher->id === $selectedTeacher) selected @endif>
                                                {{$teacher->first_name}} {{$teacher->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @if(auth()->user()->role_id === 3)
                                <div class="float-right col">
                                    <a href="" id="btn-create-classe" class="btn btn-success float-right">
                                        Thêm lớp học
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên lớp</th>
                            <th>Loại lớp</th>
                            <th>Thời gian</th>
                            <th>Tên giáo viên</th>
                            <th style="width:10%">Quản lý</th>
                            @if(auth()->user()->role_id === 3)
                                <th style="width:10%">Sửa</th>
                                <th style="width:10%">Xóa</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $each)
                            <tr>
                                <td style="color:black">
                                    <a href="">
                                        {{$each->id}}
                                    </a>
                                </td>
                                <td style="color:black">
                                    {{$each->name}}
                                </td>
                                <td style="color:black">
                                    {{$each->class_typeName}}
                                </td>
                                <td style="">
                                    @if(isset($each->start_date)|| isset($each->end_date))
                                        Từ: <span style="color:black">{{date("d/m/Y",strtotime($each->start_date))}}</span> <br>
                                        Đến: <span style="color:black">{{date("d/m/Y",strtotime($each->end_date))}}</span>
                                    @endif
                                </td>
                                <td >
                                    @foreach ($teachers as $teacher)
                                        @if ($teacher->id === $each->teacher_id)
                                            Name:
                                            <span style="color:black">{{$teacher->first_name}} {{$teacher->last_name}}</span>
                                            @if($teacher->gender === 1)
                                                <i style="font-Size:16px;color:blue" class="mdi mdi-gender-male"></i>
                                                <br>
                                            @endif
                                            @if($teacher->gender=== 2)
                                                <i style="font-Size:16px;color:hotpink" class="mdi mdi-gender-female"></i>
                                                <br>
                                            @endif
                                            @if($teacher->gender=== 3)
                                                <i style="font-Size:16px;color:green" class="mdi mdi-gender-male-female"></i>
                                                <br>
                                            @endif
                                            <span >Mail:</span>
                                            <a href="mailto::{{$teacher->email}}">
                                                {{$teacher->email}}
                                            </a>
                                        @endif
                                    @endforeach
                                </td>
                                <td>

                                    <form method="post" action='{{route('manager.classes.show',$each->id)}}'>
                                        @csrf
                                        <button type="submit" name="info" class="btn btn-info">
                                            Quản lý
                                        </button>
                                    </form>
                                </td>
                                @if(auth()->user()->role_id === 3)
                                    <td>

                                        <a href='' id="btn-edit-course" class="btn btn-primary">
                                            <i>Edit</i>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="post" action=''>
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" name="delete" class="btn btn-danger">
                                                <i>Delete</i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
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
        <script>
            $('#select-course').select2();
            $('#select-major').select2();
            $('#select-class-type').select2();
            $('#select-teacher').select2();
            $('#select-subject').select2();

            $('#modal-course').select2();
            $('#modal-major').select2();
            $('#modal-subject').select2();
            $('#modal-teacher').select2();
            $('#modal-class-type').select2();
            // $('#modal-quality-class').select2();

            function generateClassName() {
                const course = $('#modal-course option:selected').text();
                const subject = $('#modal-subject option:selected').text();
                const quality = $('#modal-quality-class').val();
                let exam='';
                if(quality <= 0 || quality >= 10) {
                    $('#modal-quality-class').val(1);
                }else if( quality > 1  && quality < 10){
                    $('#modal-classname').attr('readonly', true);
                    exam= `1.1 -> 1.${quality.trim()}`;
                }
                let classname = `${subject.trim()} 1.1 - ${course.trim()} `
                $('#modal-classname').val(classname);
                $('#modal-exam-classname').val(exam);
            }
            async function loadingSubject() {
                await $.ajax({
                            url: '{{ route('api.subject.getSubjectByMajor') }}',
                            type: 'GET',
                            data: {
                                major_id: $('#modal-major').val()
                            },
                            success: function(data){
                                $('#modal-subject').html('');
                                $.each(data, function(key, value){
                                    $('#modal-subject').append('<option value="'+value.id+'">'+value.name+'</option>');
                                });
                            }
                        });
                await generateClassName();
            }
             $(document).ready(async function() {
                $('.select-filter-course, .select-filter-teacher,.select-filter-major,.select-filter-subject,.select-filter-class-type').change(function(){
                    $('#form-filter').submit();
                });
                $('#btn-create-classe').click(async function(e){
                     await e.preventDefault();
                     await $('#modal-create-classe').modal('show');
                     await loadingSubject();

                });
                $('#modal-major').change(function(){
                    $('#modal-subject').html('');
                    loadingSubject();
                });
                 $('#modal-major,#modal-subject,#modal-course,#modal-quality-class').change(function(){
                     generateClassName();
                });
                 $('#modal-create-classe').on('hidden.bs.modal', function () {
                     $('#modal-schedule').html('');
                     $('#modal-quality-day').val('1');
                 })
                 $('#modal-btn-get-modal-schedule').click(function(e) {
                     e.preventDefault();
                     $('#modal-schedule').html('');
                     let length = $('#modal-quality-day').val();
                     let content = '';
                     for(let j = 1; j <= length; j++) {
                         $(`#modal-start-lesson-${j}`).select2();
                         $(`#modal-end-lesson-${j}`).select2();
                         $(`#modal-day-${j}`).select2();
                         content+=`
                             <div class="form-group d-flex mb-3">
                               <div style="height:100%; text-align:center">${j}</div>
                                <div class="col-md-3">
                                    <label for="modal-start-lesson-${j}">Tiết bắt đầu</label>
                                    <select class="custom-select " id="modal-start-lesson-${j}"  name="start-lesson-${j}" >
                                        <option value=""><span style="color:#ccc">Chọn tiết học</span></option>`
                                            for(let i=1; i<=12; i++){
                                                content+=`
                                                <option value="${i}">
                                                     Tiết ${i}
                                                </option>`
                                            }
                                        content+=`
                                    </select>
                                </div>

                        `;
                         content+=`

                                <div class="col-md-3">
                                    <label for="modal-end-lesson-${j}">Tiết kết thúc</label>
                                    <select class="custom-select " id="modal-end-lesson-${j}"  name="end-lesson-${j}" >
                                        <option value=""><span style="color:#ccc">Chọn tiết học</span></option>`
                         for(let i=1; i<=12; i++){
                             content+=`
                                                <option value="${i}">
                                                     Tiết ${i}
                                                </option>`
                         }
                         content+=`
                                    </select>
                                </div>
                        `;
                         content+=`
                            <div class="col-md-3">
                                    <label for="modal-day-${j}">Ngày học</label>
                                    <select class="custom-select " id="modal-day-${j}"  name="day-${j}" >
                                        <option value=""><span style="color:#ccc">Chọn ngày học</span></option>`
                         for(let i=2; i<=8; i++){
                             if(i===8){
                                 content+=`
                                                <option value="${i}">
                                                     Chủ nhật
                                                </option>`
                             }else{
                                 content+=`
                                                    <option value="${i}">
                                                         Thứ ${i}
                                                    </option>`
                             }
                         }
                            content+=`
                                        </select>
                                    </div>
                                </div>`;
                     }
                     $('#modal-schedule').html(content);
                 });
            });
            function hidemodel(idName){
                $('#'+ idName).hide();
            }
            function submitForm(formType,modalName){
                const obj=$("#form-create-"+formType);
                var formData = new FormData(obj[0]);
                $.ajax({
                    url: obj.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    async: true,
                    cache: false,
                    enctype: 'multipart/form-data',
                    success: function (response) {
                        hidemodel(modalName)
                        $.toast({
                            heading: 'Success !',
                            text: `Your ${formType} have been created.`,
                            showHideTransition: 'fade',
                            icon: 'success',
                            hideAfter: 5000,
                            position: 'top-right',
                        })
                        window.setTimeout(function(){
                            window.location.href = "{{route("manager.$table.index")}}";
                        }, 1500);

                    },
                    error: function (response) {
                        const errors = Object.values(response.responseJSON.errors);
                        errors.forEach(function (each) {
                            each.forEach(function (error) {
                                $.toast({
                                    heading: 'Error !',
                                    text: error,
                                    showHideTransition: 'fade',
                                    width: '100%',
                                    hideAfter: 5000,
                                    icon: 'error',
                                    position: 'top-right',
                                })
                            });
                        });
                    }
                });
            }
        </script>
    @endpush
    <div id="modal-create-classe" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Create {{$table}}</h5>
                    <button type="button" onclick="hidemodel('modal-create-classe')" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <form id="form-create-classe" action='{{route("manager.$table.store")}}' class="d-flex flex-column " method="POST">
                        @csrf
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-4 ">
                                <label for="modal-course">Khóa</label>
                                <select class="custom-select " id="modal-course"  name="course_id" >
                                    @foreach($courses as $course )
                                        <option value="{{ $course->id }}">
                                            {{$course->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 ">
                                <label for="modal-quality-class">Số lượng lớp </label>
                                <input  class="form-control " id="modal-quality-class" value="1" type="number"  name="quality-class">
                            </div>
                        </div>
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-4">
                                <label for="modal-major">Ngành</label>
                                <select class="custom-select " id="modal-major"  name="major_id" >
                                    @foreach($majors as $major )
                                        <option value="{{ $major->id }}">
                                            {{$major->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 ">
                                <label for="modal-subject">Môn học</label>
                                <select class="custom-select " id="modal-subject"  name="subject_id" >

                                </select>
                            </div>
                            <div class="col-md-2 ">
                                <label for="modal-class-type">Loại lớp học</label>
                                <select class="custom-select " id="modal-class-type"  name="class_type" >
                                    @foreach($class_types as $class_type => $value)
                                        <option value="{{ $value }}">
                                            {{$class_type}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-3 ">
                                <label for="modal-start_date">Thời gian bắt đầu </label>
                                <input  class="form-control " type="date" id="modal-start_date"  name="start_date">
                            </div>
                            <div class="col-md-3 ">
                                <label for="modal-end_date">Thời gian kết thúc</label>
                                <input  class="form-control " type="date" id="modal-end_date"   name="end_date">
                            </div>
                            <div class="col-md-3 ">
                                <label for="modal-quality-all_session">Tổng số buổi học</label>
                                <input  class="form-control " id="modal-quality-all_session" value="1" type="number"  name="all_session">
                            </div>
                        </div>
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-3 ">
                                <label for="modal-quality-day">Số buổi / tuần</label>
                                <input  class="form-control " id="modal-quality-day" value="1" type="number"  name="quality_day">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-info" style="margin-top:17%" id="modal-btn-get-modal-schedule">Sắp xếp lịch</button>
                            </div>
                        </div>
                        <div id="modal-schedule">

                        </div>
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-6 ">
                                <label for="modal-classname">Tên lớp </label>
                                <input  class="form-control " id="modal-classname"  name="name">
                            </div>
                            <div class="col-md-2 ">
                                <label for="modal-exam-classname">###</label>
                                <input  class="form-control " id="modal-exam-classname" readonly  name="exam-classname">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="submitForm('classe','modal-create-classe')" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection()
