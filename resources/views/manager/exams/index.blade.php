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
                            @if(Auth::user()->role_id ===2 || Auth::user()->role_id === 3)
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-major">Ngành học</label>
                                <select class="custom-select select-filter-role" id="select-major" name="majorName" >
                                    <option selected>All...</option>
                                    @foreach($majors as $major)
                                        <option value="{{ $major->id }}"
                                                @if ((string)$major->id === $selectedMajor) selected @endif>
                                            {{$major->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-course">Khóa học</label>
                                <select class="custom-select select-filter-role" id="select-course" name="courseName" >
                                    <option selected>All...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}"
                                                @if ((string)$course->id === $selectedCourse) selected @endif>
                                            {{$course->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-subject">Môn học</label>
                                <select class="custom-select select-filter-role" id="select-subject" name="subjectName" >
                                    <option selected>All...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                                @if ((string)$subject->id === $selectedSubject) selected @endif>
                                            {{$subject->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(Auth::user()->role_id === 3)
                            <div class="float-right col">
                                <a href="" id="btn-create-exam" class="btn btn-success float-right">
                                    Tạo lịch thi
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-centered mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Môn thi</th>
                            <th>Thời gian </th>
                            <th>Phòng thi, ca thi</th>
                            @if(Auth::user()->role_id === 3)
                                <th style="width:10%">edit</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $index => $each)
                            <tr style="color:black">
                                <td>
                                    {{$index+1}}
                                </td>
                                <td>
                                    {{ $each->getNameSubject() }}
                                </td>
                                <td>
                                    Ngày thi:      {{ $each->getDateConverted() }} <br>
                                    Giờ thì:       {{ $each->getTimeStart() }}
                                </td>
                                <td>
                                    {{ $each->getRoomExam() }}
                                </td>
                                @if(Auth::user()->role_id === 3)
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
            $('.select-filter-role').select2();
            $('.modal-select-exam').select2();
            $(document).ready(function() {
                $('.select-filter-role').on('change', function() {
                    $('#form-filter').submit();
                });
                $('#btn-create-exam').click(async function (e) {
                    e.preventDefault();
                    await  $.ajax({
                        url: '{{route('api.exams.getSubject')}}',
                        type: 'POST',
                        data: {
                            majorId: 1
                        },
                        success: function (data) {
                            let text =``;
                            data.data.forEach(function (item){
                                text += `<option value="${item.id}">${item.name}</option>`;
                            });
                            $('#select-modal-subject').html(text);
                        }
                    });
                    $('#modal-create-exam').modal('show');
                    $('#select-modal-major').change(function () {
                        let majorId = $(this).val();
                        $('#select-modal-subject').html('');
                        $.ajax({
                            url: '{{route('api.exams.getSubject')}}',
                            type: 'POST',
                            data: {
                                majorId: majorId
                            },
                            success: function (data) {
                                let text =``;
                                data.data.forEach(function (item){
                                    text += `<option value="${item.id}">${item.name}</option>`;
                                });
                                $('#select-modal-subject').html(text);
                            }
                        });
                    });
                    $('#btnSaveExam').click(function(e){
                        e.preventDefault();
                        $.ajax({
                            url: '{{route('manager.exams.store')}}',
                            type: 'POST',
                            data: {
                                majorId: $('#select-modal-major').val(),
                                courseId: $('#select-modal-course').val(),
                                subjectId: $('#select-modal-subject').val(),
                                date: $('#exam-date').val(),
                                timeStart: $('#exam-time').val(),
                                room: $('#exam-room').val(),
                                shift: $('#exam-shift').val(),
                            },
                            success: function (data) {
                                $('#modal-create-exam').modal('hide');
                                $.toast({
                                    heading: 'Success',
                                    text: 'Tạo lịch thi thành công',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 3500,
                                    stack: 6
                                });
                                setTimeout(function () {
                                    location.reload();
                                }, 3000);
                            },
                            error: function (data) {
                                $.toast({
                                    heading: 'Error',
                                    text: 'Tạo lịch thi thất bại',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 3500,
                                    stack: 6
                                });
                            }
                        });
                        })
                    });
            });
        </script>
    @endpush
    <div id="modal-create-exam" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Create {{$table}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-create-exam" action="{{route('manager.exams.store')}}" class="d-flex flex-column " method="POST">
                        @csrf
                        <div class="form-group d-flex">
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-modal-course">Khóa học</label>
                                <select class="custom-select modal-select-exam" id="select-modal-course" name="courseModal" >
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">
                                            {{$course->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-modal-major">Ngành học</label>
                                <select class="custom-select modal-select-exam" id="select-modal-major" name="majorModal" >
                                    @foreach($majors as $major)
                                        <option value="{{ $major->id }}">
                                            {{$major->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-modal-subject">Môn học</label>
                                <select class="custom-select modal-select-exam" id="select-modal-subject" name="subjectModal" >

                                </select>
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="form-group mb-3 w-25 mr-3">
                                <label for="exam-date">Ngày thi</label>
                                <input class="form-control" id="exam-date" type="date" name="day_exam">
                            </div>
                            <div class="form-group mb-3 w-25 mr-3">
                                <label for="exam-time">Giờ thi</label>
                                <input class="form-control" id="exam-time" type="time" name="time_start">
                            </div>
                        </div>
                        <div class="form-group d-flex ">
                            <div class="form-group mb-3 w-25 mr-3">
                                <label for="exam-room">Phòng thi</label>
                                <input class="form-control" id="exam-room" type="text" name="exam-room">
                            </div>
                            <div class="form-group mb-3 w-25 mr-3">
                                <label for="exam-shift">Ca thi</label>
                                <input class="form-control" id="exam-shift" type="number" name="exam-shift">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSaveExam" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

@endsection()
