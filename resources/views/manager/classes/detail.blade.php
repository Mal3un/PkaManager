@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12" style="display:flex;flex-direction:column">
            <div style="width:100%" class="card">
                    <div class="card-header d-flex w-100 justify-content-sm-around" >
                        <span style="font-weight:bold">
                            {{$data->name}}
                            <br>
                            <br>
                            <span>Giáo viên: </span>
                            @isset($teacher)
                                {{$teacher->first_name .' '.$teacher->last_name}}
                            @endisset
                        </span>
                        <div class="input-group w-25 mr-3">
                            <label for="select-lesson">Buổi học</label>
                            <select class="custom-select select-filter-lesson" id="select-lesson" name="lesson" >
                                <option selected>Chọn buổi học</option>
                                @foreach($lesson as $each )
                                    <option value="{{ $each }}">
{{--                                            @if ((string)$each === $selectedLesson) selected @endif>--}}
                                        Buổi {{$each}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="w-100">
                                <form class="w-100 d-flex mt-3 justify-content-sm-between" method="post" action="">
                                    @csrf
                                    <input id="class_id" type="hidden" name="class_id" value="{{$data->id}}">
                                    <button id="btn-point-list" type="submit" name="point_list" class="btn btn-primary">
                                        {{Auth::user()->role_id === 1 ? 'Xem thông tin điểm danh' : 'Điểm danh lớp học'}}
                                    </button>
                                    @if(Auth::user()->role_id === 2 || Auth::user()->role_id === 3)
                                        <button id="btn-open-score" type="submit" name="score" class="btn btn-success">
                                            Nhập điểm
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" >
                        <h4 style="width:100%;text-align:center;margin-bottom:14px">Danh sách lớp</h4>
                        <div>
                            <table class="table table-striped table-centered mb-0 w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sinh viên</th>
                                        <th>Giới tính</th>
                                        <th>Ngày sinh</th>
                                        <th>Gmail</th>
                                        <th>Số buổi nghỉ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $key=>$each )
                                        <tr>
                                            <td style="color:black">
                                                    {{$key+1}}
                                            </td>
                                            <td style="">
                                                <span style="color:black">{{$each->first_name}} {{$each->last_name}}</span>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <span style="color:black">{{date("d/m/Y",strtotime($each->birthdate))}}</span>
                                                <br>
                                            </td>
                                            <td>
                                                <a href="mailto::{{$each->email}}">
                                                    {{$each->email}}
                                                </a>
                                            </td>
                                            <td>
                                                <span style="color:black">{{count($lesson) - $each->status}} / {{count($lesson)}}</span>
                                            </td>
                                            @if((count($lesson) - $each->status) < ((count($lesson)*0.4)-1))
                                                <td style="text-align:center">
                                                    <i   class="mdi mdi-emoticon " style="color:#42d29d;font-size:30px;"></i>
                                                    <h6 style="margin:0px" >Tốt</h6>
                                                </td>
                                            @endif
                                            @if((count($lesson) - $each->status) >= ((count($lesson)*0.4)-1) && (count($lesson) - $each->status) < ((count($lesson)*0.4)))
                                                <td style="text-align:center">
                                                    <i   class="mdi mdi-emoticon-neutral " style="color:#f9bc0d;font-size:30px;"></i>
                                                    <h6 style="margin:0px" >Cảnh báo</h6>
                                                </td>
                                            @endif
                                            @if((count($lesson) - $each->status) >= ((count($lesson)*0.4)))
                                                <td style="text-align:center">
                                                    <i  class="mdi mdi-emoticon-sad " style="color:#fa6767;font-size:30px;"></i>
                                                    <h6 style="margin:0px" >Vi phạm</h6>
                                                </td>
                                            @endif


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function loadingInfoListPoint(){
                var lesson = $('#select-lesson').val();
                var class_id = $('#class_id').val();
                $.ajax({
                    url: '{{route('api.classes.point_list')}}',
                    type: 'POST',
                    data: {
                        lesson: lesson,
                        class_id: class_id
                    },
                    success: async function(data) {
                        let check = '';
                        if($('#checkRoleUser').val() == 1){
                            check='disabled';
                        }
                        let text = ``;
                        let status1 = 0;
                        let status2 = 0;
                        let status3 = 0;
                        let total = data.data.length;
                        let note = data.data[0].note;
                        data.data.forEach(function(each,index) {
                            if(each.status === 1) {
                                status1++;
                            }
                            else if(each.status === 2) {
                                status2++;
                            }
                            else if(each.status === 3) {
                                status3++;
                            }
                            text +=`
                                        <tr>
                                            <td style="color:black">
                                                    ${index+1}
                                            </td>
                                            <td style="">
                                                <span style="color:black">${each.info.first_name} ${each.info.last_name}</span>
                                            </td>
                                            <td>
                                                <span style="color:black">${each.info.birthdate}</span>
                                                <br>
                                            </td>
                                            <td>
                                                <a href="mailto::${each.info.email}">
                                                    ${each.info.email}
                                                </a>
                                            </td>`;
                            if(each.status == 1) {
                                text+=`
                                                    <td>
                                                        <input type="radio"  checked   id="check-${each.info.id}" name="id${each.info.id}" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="radio" ${check}  id="check-${each.info.id}" name="id${each.info.id}" value="2">
                                                    </td>
                                                    <td>
                                                        <input type="radio" ${check}  id="check-${each.info.id}" name="id${each.info.id}" value="3">
                                                    </td>
                                                `;
                            }
                            else if(each.status ==2) {
                                text+=`
                                                        <td>
                                                            <input type="radio" ${check}   id="check-${each.info.id}" name="id${each.info.id}" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="radio"  checked id="check-${each.info.id}" name="id${each.info.id}" value="2">
                                                        </td>
                                                        <td>
                                                            <input type="radio" ${check}  id="check-${each.info.id}" name="id${each.info.id}" value="3">
                                                        </td>
                                                    `;
                            }
                            else if(each.status ==3) {
                                text+=`
:                                                           <td>
                                                                <input type="radio"  ${check}  id="check-${each.info.id}" name="id${each.info.id}" value="1">
                                                            </td>
                                                            <td>
                                                                <input type="radio" ${check}  id="check-${each.info.id}" name="id${each.info.id}" value="2">
                                                            </td>
                                                            <td>
                                                                <input type="radio"  checked  id="check-${each.info.id}" name="id${each.info.id}" value="3">
                                                            </td>
                                                        `;
                            }
                            text+=`</tr>`;
                        });
                        let session =  $('#select-lesson').val();
                        $('#modal-list-point-note').val(note);
                        $('#modal-list-point-total').html('Tổng số: '+ total + " sinh viên");
                        $('#modal-list-point-status1').html('Đi học: '+ status1 + " sinh viên");
                        $('#modal-list-point-status2').html('Đi muộn: '+ status2 + " sinh viên");
                        $('#modal-list-point-status3').html('Vắng mặt: '+ status3 + " sinh viên");
                        $('#modal-title-session').html('Buổi thứ '+ session);
                        $('#modal-set-point_list').append(`${text}`);
                    },
                    error: function(data) {
                        $.toast(
                            {
                                heading: 'Lỗi',
                                text: 'Có lỗi xảy ra',
                                showHideTransition: 'fade',
                                icon: 'error',
                                position: 'top-right',
                                hideAfter: 5000
                            }
                        );
                    }
                });
            }

            function loadingInfoScore(){
                let class_id = $('#class_id').val();
                $.ajax({
                    url:'{{route('api.classes.score')}}',
                    type: 'POST',
                    data: {
                        class_id: class_id,
                    },
                    success: async function(data) {
                        let text = ``;

                        data.data.forEach(function (each,index) {
                            if(each.listpoint_score === null){
                                each.listpoint_score = '';
                            }
                            if(each.midterm_score === null){
                                each.midterm_score = '';
                            }
                            if(each.note === null){
                                each.note = '';
                            }
                            text +=
                                `
                                        <tr>
                                            <td style="color:black">
                                                    ${index+1}
                                            </td>
                                            <td style="">
                                                <span style="color:black">${each.student_id.first_name} ${each.student_id.last_name}</span>
                                            </td>
                                            <td>
                                                <span style="color:black">${each.student_id.birthdate}</span>
                                                <br>
                                            </td>
                                            <td>
                                                <a href="mailto::${each.student_id.email}">
                                                    ${each.student_id.email}
                                                </a>
                                            </td>
                                            <td>

                                                <input style="width:40%"  value="${each.listpoint_score}" id="${each.student_id.id}-listpoint" name="${each.student_id.id}-listpoint" class="form-control">
                                            </td>
                                            <td>
                                                <input style="width:40%"  value="${each.midterm_score}" id="${each.student_id.id}-mid" name="${each.student_id.id}-mid" class="form-control">
                                            </td>
                                            <td>
                                                <input type="hidden" name="HS" value="">
                                                <textarea id="${each.student_id.id}-note" class="form-control student-note-in-class" rows="3" value="${each.note}" placeholder="note">${each.note}</textarea>
                                            </td>
                                        </tr>
                                `;
                        });
                        $('#modal-set-score-body').append(`${text}`);
                    },
                    error: async function(){
                        $.toast(
                            {
                                heading: 'Lỗi',
                                text: 'Có lỗi xảy ra',
                                showHideTransition: 'fade',
                                icon: 'error',
                                position: 'top-right',
                                hideAfter: 5000
                            }
                        );
                    }
                })
            }


            $('#select-lesson').select2({
                placeholder: 'Chọn buổi học',
                allowClear: true
            });
            $('.select-filter-lesson').select2();


            $(document).ready(async function() {
                $('#modal-set-point-list').on('hidden.bs.modal',function() {
                    // $('#modal-list-point-note').val('');
                    location.reload();
                });

                $('#btn-point-list').on('click', function(e) {
                    e.preventDefault();
                    $('#modal-set-point_list').html('');
                    var lesson = $('#select-lesson').val();
                    if(lesson === 'Chọn buổi học') {
                        $.toast(
                            {
                                heading: 'Lỗi',
                                text: 'Bạn chưa chọn buổi học',
                                showHideTransition: 'slide',
                                icon: 'error',
                                position: 'top-right',
                                hideAfter: 5000
                            }
                        );
                    }else {
                        loadingInfoListPoint();
                        $('#modal-set-point-list').modal('show');
                        $('#btn-update-point-list').html('Cập nhật');
                        $('#btn-update-point-list').attr('class','btn btn-primary float-right ');
                    }
                });
                $('#btn-open-score').on('click', function(e) {
                    e.preventDefault();
                    $('#modal-set-score-body').html('');
                    loadingInfoScore();
                    $('#modal-set-score').modal('show');
                    $('#btn-update-score').html('Cập nhật điểm');
                    $('#btn-update-score').attr('class','btn btn-primary float-right mt-3 ');
                });

                $('#btn-update-point-list').on('click', function(e) {
                    e.preventDefault();
                    let raw = $('#form-set-point-list').serialize();
                    let session =  $('#select-lesson').val();
                    let class_id = $('#class_id').val();
                    let note = $('#modal-list-point-note').val();
                    $.ajax({
                        url: '{{route('api.classes.setPointList')}}',
                        type: 'POST',
                        data: {
                            classid:class_id,
                            lesson: session,
                            status: raw,
                            note: note
                        },
                        success: async function(data){
                            $.toast(
                                {
                                    heading: 'Thành công',
                                    text: 'Cập nhật điểm danh thành công',
                                    showHideTransition: 'fade',
                                    icon: 'success',
                                    position: 'top-right',
                                    hideAfter: 5000
                                },
                            );
                            $('#modal-set-point_list').html('');
                            loadingInfoListPoint();
                            $('#btn-update-point-list').html('Đã cập nhật');
                            $('#btn-update-point-list').attr('class','btn btn-success float-right');
                        },
                        error: function(data) {
                            $.toast(
                                {
                                    heading: 'Lỗi',
                                    text: 'Có lỗi xảy ra',
                                    showHideTransition: 'fade',
                                    icon: 'error',
                                    position: 'top-right',
                                    hideAfter: 5000
                                }
                            );
                        }
                    });
                });
                $('#btn-update-score').on('click',async function (e){
                    e.preventDefault();
                    let raw = $('#form-set-score').serialize();
                    let class_id = $('#class_id').val();

                    let arr = $('.student-note-in-class').map(await function(){
                        return $(this).attr('id') + '=' + $(this).val();
                    }).get();
                    $.ajax({
                        url: '{{route('api.classes.setScore')}}',
                        type: 'POST',
                        data: {
                            classid:class_id,
                            data: raw,
                            arr: arr
                        },
                        success: async function(data){
                            $.toast(
                                {
                                    heading: 'Thành công',
                                    text: 'Cập nhật điểm thành công',
                                    showHideTransition: 'fade',
                                    icon: 'success',
                                    position: 'top-right',
                                    hideAfter: 5000
                                },
                            );
                            $('#modal-set-score-body').html('');
                            loadingInfoScore();
                            $('#btn-update-score').html('Đã cập nhật');
                            $('#btn-update-score').attr('class','btn btn-success float-right mt-3');
                        },
                        error: function() {
                            $.toast(
                                {
                                    heading: 'Lỗi',
                                    text: 'Có lỗi xảy ra',
                                    showHideTransition: 'fade',
                                    icon: 'error',
                                    position: 'top-right',
                                    hideAfter: 5000
                                }
                            );
                        }
                    })
                })
                $('#btn-submit-with-file').click(function() {
                    $('#file_listpoint').click();
                });
                $('#file_listpoint').change(function() {
                    let file = $('#file_listpoint')[0].files[0];
                    let formData = new FormData();
                    formData.append('file', file);
                   $.ajax({
                          url: '{{route('api.listpoint.import')}}',
                          type: 'POST',
                          data: formData,
                          processData: false,
                          contentType: false,
                            csrf: true,
                          success: function(data) {
                              let $info = data.data;
                              let $body = $('#modal-set-point_list');
                              Object.keys($info).forEach($key => {
                                  $index = $key - 1;
                                  $tr = $body.children().eq($index);
                                  if ($info[$key] === 1) {
                                      $tr.children().eq(4).children().eq(0).prop('checked', true);
                                  } else if ($info[$key] === 2) {
                                      $tr.children().eq(5).children().eq(0).prop('checked', true);
                                  } else {
                                      $tr.children().eq(6).children().eq(0).prop('checked', true);
                                  }
                              });
                            $.toast(
                                 {
                                      heading: 'Thành công',
                                      text: 'Nhập điểm danh thành công',
                                      showHideTransition: 'fade',
                                      icon: 'success',
                                      position: 'top-right',
                                      hideAfter: 5000
                                 },
                            );

                          },
                          error: function(data) {
                            $.toast(
                                 {
                                      heading: 'Lỗi',
                                      text: 'Có lỗi xảy ra',
                                      showHideTransition: 'fade',
                                      icon: 'error',
                                      position: 'top-right',
                                      hideAfter: 5000
                                 }
                            );
                          }
                     });
                   });
            });
        </script>
    @endpush
    <div id="modal-set-point-list" style="padding:20px" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
        @if(Auth::user()->role_id !== 2 && Auth::user()->role_id !==3)
            <input type="hidden" id="checkRoleUser" value="1">
        @endif
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 id="modal-title-session" class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <form id="form-set-point-list" action="">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên sinh viên</th>
                                <th>Ngày sinh</th>
                                <th>Gmail</th>
                                <th>Đi học</th>
                                <th>Đi muộn</th>
                                <th>Nghỉ</th>
                            </tr>
                            </thead>
                            <tbody id="modal-set-point_list">

                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex" style="float:left;position:relative;left:10px;width:100%">
                        <div class="info-listpoint-status-session w-25">
                            <span id="modal-list-point-total"></span><br>
                            <span id="modal-list-point-status1"></span><br>
                            <span id="modal-list-point-status2"></span><br>
                            <span id="modal-list-point-status3"></span><br>
                        </div>

                        <div class="info-listpoint-status-session w-75">
                            <textarea @if(Auth::user()->role === 1) readonly @endif id="modal-list-point-note" class="form-control" rows="3" placeholder="Ghi chú"></textarea>
                        </div>
                    </div>
                    @if(Auth::user()->role_id === 2 || Auth::user()->role_id === 3)
                        <input type="file"  name="file_listpoint" id="file_listpoint"  style="width:0; height: 0">
                        <button type="submit" id="btn-submit-with-file" class="btn btn-info">
                            <i class="mdi mdi-file-excel"></i> Điểm danh bằng files
                        </button>
                        <button type="submit" id="btn-update-point-list" class="">

                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="modal-set-score" style="padding:20px" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 id="modal-title" class="modal-title">Điểm số</h5>
                    <button type="button" onclick="hidemodel('modal-set-score')" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <form id="form-set-score" action="">
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên sinh viên</th>
                                <th>Ngày sinh</th>
                                <th>Gmail</th>
                                <th style="width:10%">Điểm chuyên cần</th>
                                <th style="width:10%">Điểm giữa kì</th>
                                <th style="width:25%">Note</th>
                            </tr>
                            </thead>
                            <tbody id="modal-set-score-body">

                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
{{--                    <div class="d-flex" style="float:left;position:relative;left:10px;width:100%">--}}
{{--                        <div class="info-listpoint-status-session w-25">--}}
{{--                            <span id="modal-list-point-total"></span><br>--}}
{{--                            <span id="modal-list-point-status1"></span><br>--}}
{{--                            <span id="modal-list-point-status2"></span><br>--}}
{{--                            <span id="modal-list-point-status3"></span><br>--}}
{{--                        </div>--}}
{{--                        <div class="info-listpoint-status-session w-75">--}}
{{--                            <textarea id="modal-list-point-note" class="form-control" rows="3" placeholder="Ghi chú"></textarea>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <button type="submit" id="btn-update-score" class="">
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection()
