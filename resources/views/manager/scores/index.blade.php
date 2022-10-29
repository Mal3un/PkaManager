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
                            @if(Auth::user()->role_id ===3)
                                <div class="input-group mb-3 w-15 mr-3">
                                    <label for="select-major">Ngành</label>
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
                                <div class="input-group mb-3 w-15 mr-3">
                                    <label for="select-course">Khóa</label>
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
                            <div class="input-group mb-3 w-15 mr-3">
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
                            @if(Auth::user()->role_id ===3)
                                <div class="input-group mb-3 w-15 mr-3">
                                    <label for="select-students">Sinh viên</label>
                                    <select class="custom-select select-filter-role" id="select-students" name="studentsName" >
                                        <option selected>All...</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    @if ((string)$student->id === $selectedStudent) selected @endif>
                                                {{$student->first_name}} {{$student->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-centered mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            @if(Auth::user()->role_id ===3)
                            <th>Sinh viên</th>
                            @endif
                            <th>Thông tin học phần</th>
                            <th>Điểm CC</th>
                            <th>Điểm GK</th>
                            <th>Điểm CK</th>
                            <th>Điểm Tổng kết</th>
                            <th>Xếp loại</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $index => $each)
                            <tr style="color:black">
                                <td>{{ $index + 1 }}</td>
                                @if(Auth::user()->role_id ===3)
                                <td>{{ $each->getStudentName() }} <br> ({{ $each->getDateConverted() }})</td>
                                @endif
                                <td>{{ $each->subject->name }} <br> ({{ $each->subject->number_credits }} tín chỉ ) </td>
                                <td style="width:5%">{{ $each->listpoint_score }}</td>
                                <td style="width:5%">{{ $each->midterm_score }}</td>
                                @if(Auth::user()->role_id ===3)
                                <td style="width:10%">
                                    <input style="width:40px;margin-right:10px" type="number" id="lastterm_score_{{$each->student_id}}_{{$each->subject_id}}" value="{{ $each->lastterm_score}}">
                                    <button class="btn btn-primary btn-sm" id="btn_{{$each->student_id}}_{{$each->subject_id}}" onclick="updateScore( {{$each->student_id}} ,{{$each->subject_id}} )"><i class="uil-pen"></i></button>
                                </td>
                                @else
                                    <td style="width:5%">{{ $each->lastterm_score }}</td>
                                @endif
                                <td>
                                    @if(isset($each->listpoint_score) && isset($each->midterm_score) && isset($each->lastterm_score))
                                        {{ $each->totalScore() }}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($each->listpoint_score) && isset($each->midterm_score) && isset($each->lastterm_score))
                                        {{ $each->getRankByScore($each->totalScore()) }}
                                    @endif
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
        <script>
            $('.select-filter-role').select2();
            $(document).ready(function() {
                $('.select-filter-role').on('change', function() {
                    $('#form-filter').submit();
                });
            });
        </script>
        @if(Auth::user()->role_id ===3)
        <script>
            function updateScore(stdid,subid){
                let score = $('#lastterm_score_'+stdid+'_'+subid).val();
                if(score > 10 || score < 0){
                    $.toast({
                        heading: 'Lỗi',
                        text: 'Điểm không hợp lệ',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }else{
                    $.ajax({
                        url: '{{ route('api.scores.updateLastScore') }}',
                        type: 'POST',
                        data: {
                            stdid: stdid,
                            subid: subid,
                            score: score,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data){
                            $('#btn_'+stdid+'_'+subid).html('<i class="uil-check"></i>');
                            $('#btn_'+stdid+'_'+subid).attr('class','btn btn-success btn-sm');
                        },
                        error: function(data){

                        }
                    })

                }
            }
        </script>
        @endif
    @endpush


@endsection()
