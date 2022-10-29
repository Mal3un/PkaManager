@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header " >
                        <div class="form-group d-flex">
                        @if(Auth::user()->role_id === 3)
                                <div class="input-group mb-3 w-15 mr-3">
                                    <label for="select-student">Sinh viên</label>
                                    <select class="custom-select select-filter-student" id="select-student" name="student" >
                                        <option selected>Chọn sinh viên</option>
                                        @foreach($students as $student )
                                            <option value="{{$student->id}} ">
                                                {{$student->first_name}} {{$student->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        @endif
                            <div class="w-25" id="info-student-select">

                            </div>
                        </div>
                </div>
                <div class="card-body d-flex">
                    @if(Auth::user()->role_id === 1)
                        <input type="hidden" id="student_id" value="{{$students}}">
                    @endif
                    <table class="table table-bordered table-centered mb-0 text-center w-75">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Thứ 2</th>
                            <th>Thứ 3</th>
                            <th>Thứ 4</th>
                            <th>Thứ 5</th>
                            <th>Thứ 6</th>
                            <th>Thứ 7</th>
                            <th>Chủ nhật</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for($i=1;$i<=12;$i++)
                                @if($i===6)
                                    <tr>
                                        <td style="font-weight:bold;width:8%;">Tiết {{$i}}</td>
                                        <td id="monday-t{{$i}}"></td>
                                        <td id="tuesday-t{{$i}}"></td>
                                        <td id="wednesday-t{{$i}}"></td>
                                        <td id="thursday-t{{$i}}"></td>
                                        <td id="friday-t{{$i}}"></td>
                                        <td id="saturday-t{{$i}}"></td>
                                        <td id="sunday-t{{$i}}"></td>
                                    </tr>
                                    <tr style="height:32px;border:2px solid #eef2f7" >
                                    </tr>
                                @endif
                                @if($i!==6)
                                    <tr>
                                        <td style="font-weight:bold;width:8%;">Tiết {{$i}}</td>
                                        <td id="monday-t{{$i}}"></td>
                                        <td id="tuesday-t{{$i}}"></td>
                                        <td id="wednesday-t{{$i}}"></td>
                                        <td id="thursday-t{{$i}}"></td>
                                        <td id="friday-t{{$i}}"></td>
                                        <td id="saturday-t{{$i}}"></td>
                                        <td id="sunday-t{{$i}}"></td>
                                    </tr>
                                @endif
                            @endfor
                        </tbody>
                    </table>
{{--                    <div class="w-25 ml-4">--}}
{{--                        <h4 class="header-2">Thời gian các tiết học</h4>--}}
{{--                        <table class="table table-bordered table-centered mb-0 text-center">--}}
{{--                            <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>Tiết</th>--}}
{{--                                    <th>Thời gian</th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                @for($i=1;$i<=12;$i++)--}}
{{--                                    <tr>--}}
{{--                                        <td>Tiết {{$i}}</td>--}}
{{--                                        <td id="time-t{{$i}}"></td>--}}
{{--                                    </tr>--}}
{{--                                @endfor--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $('#select-student').select2();
            @if (Auth::user()->role_id ===3)
                $(document).ready(async function() {
                $('.select-filter-student').change(function(e){
                    for(let i=1;i<=12;i++){
                        $('#monday-t'+i).html('');
                        $('#monday-t'+i).attr('style','');
                        $('#tuesday-t'+i).html('');
                        $('#tuesday-t'+i).attr('style','');
                        $('#wednesday-t'+i).html('');
                        $('#wednesday-t'+i).attr('style','');
                        $('#thursday-t'+i).html('');
                        $('#thursday-t'+i).attr('style','');
                        $('#friday-t'+i).html('');
                        $('#friday-t'+i).attr('style','');
                        $('#saturday-t'+i).html('');
                        $('#saturday-t'+i).attr('style','');
                        $('#sunday-t'+i).html('');
                        $('#sunday-t'+i).attr('style','');
                    }
                    $.ajax({
                        url: '{{ route('api.scheduleSt.Schedule') }}',
                        type: 'POST',
                        data: {
                            student: $(this).val(),
                        },
                        success: function(data) {
                            let style = 'background-color: lightgreen; color: #0c0c0c; cursor: pointer; font-weight: 550;';
                            let info = '';
                            info += `
                                Name:
                                    <span style="color:black">${data.data[1].first_name + ' ' + data.data[1].last_name} </span>
                            `
                            if(data.data[1].gender === 1){
                               info += `<i style="font-Size:16px;color:blue" class="mdi mdi-gender-male"></i>
                                <br>`
                            }
                            else if(data.data[1].gender === 2){
                                info += `<i style="font-Size:16px;color:hotpink" class="mdi mdi-gender-female"></i>
                                <br>`
                            }
                            else if(data.data[1].gender=== 3){
                                info += `<i style="font-Size:16px;color:green" class="mdi mdi-gender-male-female"></i>
                                <br>`
                            }
                            info += ` Ngày sinh:
                                    <span style="color:black">${data.data[1].birthdate}</span>
                                    <br>
                                    `
                            info += ` Khóa:
                                    <span style="color:black">${data.data[1].course}</span>
                                    <br>
                                    Ngành:
                                    <span style="color:black">${data.data[1].major}</span>
                                    <br>`
                            $('#info-student-select').html(info);
                            for(let day in data.data[0]){
                                switch (day){
                                    case 'M':
                                        for(let time in data.data[0][day]){
                                            $('#monday-t'+time).html(data.data[0][day][time]);
                                            $('#monday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'T':
                                        for(let time in data.data[0][day]){
                                            $('#tuesday-t'+time).html(data.data[0][day][time]);
                                            $('#tuesday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'W':
                                        for(let time in data.data[0][day]){
                                            $('#wednesday-t'+time).html(data.data[0][day][time]);
                                            $('#wednesday-t'+time).attr('style',`${style}`);

                                        }
                                        break;
                                    case 't':
                                        for(let time in data.data[0][day]){
                                            $('#thursday-t'+time).html(data.data[0][day][time]);
                                            $('#thursday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'F':
                                        for(let time in data.data[0][day]){
                                            $('#friday-t'+time).html(data.data[0][day][time]);
                                            $('#friday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'S':
                                        for(let time in data.data[0][day]){
                                            $('#saturday-t'+time).html(data.data[0][day][time]);
                                            $('#saturday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 's':
                                        for(let time in data.data[0][day]){
                                            $('#sunday-t'+time).html(data.data[0][day][time]);
                                            $('#sunday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                }
                            }
                        }
                    })
                });
            });
            @endif
            @if(Auth::user()->role_id === 1)
                $(document).ready(async function() {
                    for(let i=1;i<=12;i++){
                        $('#monday-t'+i).html('');
                        $('#monday-t'+i).attr('style','');
                        $('#tuesday-t'+i).html('');
                        $('#tuesday-t'+i).attr('style','');
                        $('#wednesday-t'+i).html('');
                        $('#wednesday-t'+i).attr('style','');
                        $('#thursday-t'+i).html('');
                        $('#thursday-t'+i).attr('style','');
                        $('#friday-t'+i).html('');
                        $('#friday-t'+i).attr('style','');
                        $('#saturday-t'+i).html('');
                        $('#saturday-t'+i).attr('style','');
                        $('#sunday-t'+i).html('');
                        $('#sunday-t'+i).attr('style','');
                    }
                    $.ajax({
                        url: '{{ route('api.scheduleSt.Schedule') }}',
                        type: 'POST',
                        data: {
                            student: $('#student_id').val(),
                        },
                        success: function(data) {
                            let style = 'background-color: lightgreen; color: #0c0c0c; cursor: pointer; font-weight: 550;';
                            let info = '';
                            info += `
                                Name:
                                    <span style="color:black">${data.data[1].first_name + ' ' + data.data[1].last_name} </span>
                            `
                            if(data.data[1].gender === 1){
                                info += `<i style="font-Size:16px;color:blue" class="mdi mdi-gender-male"></i>
                                <br>`
                            }
                            else if(data.data[1].gender === 2){
                                info += `<i style="font-Size:16px;color:hotpink" class="mdi mdi-gender-female"></i>
                                <br>`
                            }
                            else if(data.data[1].gender=== 3){
                                info += `<i style="font-Size:16px;color:green" class="mdi mdi-gender-male-female"></i>
                                <br>`
                            }
                            info += ` Ngày sinh:
                                    <span style="color:black">${data.data[1].birthdate}</span>
                                    <br>
                                    `
                            info += ` Khóa:
                                    <span style="color:black">${data.data[1].course}</span>
                                    <br>
                                    Ngành:
                                    <span style="color:black">${data.data[1].major}</span>
                                    <br>`
                            $('#info-student-select').html(info);
                            for(let day in data.data[0]){
                                switch (day){
                                    case 'M':
                                        for(let time in data.data[0][day]){
                                            $('#monday-t'+time).html(data.data[0][day][time]);
                                            $('#monday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'T':
                                        for(let time in data.data[0][day]){
                                            $('#tuesday-t'+time).html(data.data[0][day][time]);
                                            $('#tuesday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'W':
                                        for(let time in data.data[0][day]){
                                            $('#wednesday-t'+time).html(data.data[0][day][time]);
                                            $('#wednesday-t'+time).attr('style',`${style}`);

                                        }
                                        break;
                                    case 't':
                                        for(let time in data.data[0][day]){
                                            $('#thursday-t'+time).html(data.data[0][day][time]);
                                            $('#thursday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'F':
                                        for(let time in data.data[0][day]){
                                            $('#friday-t'+time).html(data.data[0][day][time]);
                                            $('#friday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 'S':
                                        for(let time in data.data[0][day]){
                                            $('#saturday-t'+time).html(data.data[0][day][time]);
                                            $('#saturday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                    case 's':
                                        for(let time in data.data[0][day]){
                                            $('#sunday-t'+time).html(data.data[0][day][time]);
                                            $('#sunday-t'+time).attr('style',`${style}`);
                                        }
                                        break;
                                }
                            }
                        }
                    })
                });
            @endif
        </script>
    @endpush
@endsection()
