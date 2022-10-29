@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12" style="display:flex;flex-direction:column;justify-content:space-around;height:100vh">
            <div style="width:100%" class="card">
                <div class="card-header " >
                    <h4 style="text-align:center;padding:20px 0px;color:#4f97fc" class="">Phân công giảng dậy</h4>
                    <form id="form-filter">
                        <div class="form-group d-flex">
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-teacher">Tên giáo viên</label>
                                <select class="custom-select select-filter-teacher" id="select-teacher" name="teacher" >
                                    <option >All...</option>
                                    @foreach($teachers as $each)
                                        <option value="{{ $each->id }}">
                                            {{$each->first_name}} {{$each->last_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 w-50 mr-3">
                                <label for="select-classe">Tên lớp học</label>
                                <select class="custom-select select-filter-class" multiple id="select-classe" name="classes[]" >
                                    <option selected>Nothing...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">
                                           {{$class->name}}
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
                            <th>Thông tin giáo viên</th>
                            <th>Thông tin lớp học  </th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody>
                                <tr id="table-tr-info">
                                    <td width="40%" id="teacher-info">

                                    </td>
                                    <td width="40%" id="class-info">

                                    </td>
                                    <td width="10%" id="btn_division">
                                        <button class="btn btn-primary" id="btn-set-division">Phân công</button>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div style="width:100%" class="card">
                <div class="card-header " >
                    <h4 style="text-align:center;padding:20px 0px; color:#388e3c" class="">Chỉnh sửa phân công</h4>
                    <form id="form-filter2">
                        <div class="form-group d-flex">
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-teacher2">Tên giáo viên</label>
                                <select class="custom-select select-filter-teacher2" id="select-teacher2" name="teacher" >
                                    <option selected>All...</option>
                                    @foreach($teachers as $each)
                                        <option value="{{ $each->id }}">
                                            {{$each->first_name}} {{$each->last_name}}
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
                            <th>Thông tin giáo viên</th>
                            <th>Các lớp đang dạy </th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="table-tr-info">
                            <td width="40%" id="teacher-info2">

                            </td>
                            <td width="40%" id="class-info2">

                            </td>
                            <td width="10%" id="btn_division">
                                <button class="btn btn-success" id="btn-edit-division">Chỉnh sửa</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
        <script>
            $('#select-teacher,#select-teacher2').select2();
            $('#select-classe').select2();
            $(document).ready(function() {
                $('.select-filter-teacher,.select-filter-class').change(function(){
                    loadInfo();
                });
                $('#btn-set-division').click(async function(){
                    let class_id='';
                    let stt = $('.input_class_id').length;
                    for(let i = 0; i < stt; i++){
                        class_id += $(`#input_class_id_${i}`).val() + ',';
                    }
                await $.ajax({
                        url: "{{route('api.division.set')}}",
                        type: 'GET',
                        data: {
                            teacher_id : $('#teacher-info').find('input').val(),
                            class_id : class_id,
                        },
                        success: function (response) {
                            $.toast({
                                heading: 'Success !',
                                text: `Phân công thành công`,
                                showHideTransition: 'fade',
                                icon: 'success',
                                hideAfter: 5000,
                                position: 'top-right',
                            })
                            window.setTimeout(function(){
                                window.location.href = "{{route("manager.division.index2")}}";
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
                });
                $('#select-teacher2').change(function(){
                    loadInfo2();
                });
                $('#btn-edit-division').click(async function(e){
                    e.preventDefault();
                    $('#modal-table-edit-vision').html('');
                    if($('#form-filter2').serialize() === '' || $('#form-filter2').serialize() === "teacher=All..."){
                        $.toast({
                            heading: 'Error !',
                            text: `Vui lòng chọn giáo viên`,
                            showHideTransition: 'fade',
                            width: '100%',
                            hideAfter: 5000,
                            icon: 'error',
                            position: 'top-right',
                        })}
                    else {
                        $.ajax({
                            url: "{{route('api.division.edit')}}",
                            type: 'GET',
                            dataType: 'json',
                            data: $('#form-filter2').serialize(),
                            processData: false,
                            contentType: false,
                            async: true,
                            cache: false,
                            enctype: 'multipart/form-data',
                            success: function (response) {
                                let classs= ``;
                                for (const [key, value] of Object.entries(response.data[1])) {
                                    classs += `<tr>
                                        <td><span style="color:black" class="" >- ${response.data[0].first_name} ${response.data[0].last_name}</span></td>
                                        <td><span style="color:black" class="" >- ${value}</span> </td>
                                        <td><button type="button" onclick="unsetDivision(${response.data[0].id},${key})" class="btn btn-danger btn-sm">Delete</button></td>
                                    </tr>`;
                                }
                                $('#modal-table-edit-vision').append(`${classs}`);
                            },
                            error: function (response) {

                            }
                        });
                        $('#modal-edit-division').show();
                    }
                });
            });
            function hidemodel(idName){
                $('#'+ idName).hide();
                loadInfo2();
            }
            function loadInfo(){
                $.ajax({
                    url: "{{route('api.division.info')}}",
                    type: 'GET',
                    dataType: 'json',
                    data: $('#form-filter').serialize(),
                    processData: false,
                    contentType: false,
                    async: true,
                    cache: false,
                    enctype: 'multipart/form-data',
                    success: function (response) {
                        $('#teacher-info,#class-info').html('');
                        let gender = ``;
                        if(response.data[0] != null) {
                            if (response.data[0].gender === 1) {
                                gender = `<i style="font-Size:16px;color:blue" class="mdi mdi-gender-male"></i>
                                                <br>`;
                            } else if (response.data[0].gender === 2) {
                                gender = `<i style="font-Size:16px;color:hotpink" class="mdi mdi-gender-female"></i>
                                                <br>`
                            } else if (response.data[0].gender === 3) {
                                gender = ` <i style="font-Size:16px;color:green" class="mdi mdi-gender-male-female"></i>
                                                <br>`
                            }
                            $('#teacher-info')
                                .append(`
                                <input type="hidden" value="${response.data[0].id}"/>
                                Name: <span style="color:black" class="" >${response.data[0].first_name} ${response.data[0].last_name} </span> ${gender}
                                <span> Mail: </span>
                                <a href="mailto::${response.data[0].email}">
                                ${response.data[0].email}
                                </a>
                                <br>
                                Address:
                                <span style="color:black" class="" >${response.data[0].address}</span>
                               `);
                        }
                        if(response.data[1] != null) {
                            for(let i = 0; i < response.data[1].length; i++) {
                                let class_mode = ``;
                                let class_type = ``;
                                let datetime = ``;
                                if(response.data[1][i].class_mode == 1){
                                    class_mode= `<span style="color:black" class="" >Chính thức</span> <br>`
                                }else{
                                    class_mode= `<span style="color:black" class="" >Cải thiện</span> <br>`
                                }
                                if(response.data[1][i].class_type == 1){
                                    class_type = `<span style="color:black" class="" >Lý thuyết</span> <br>`
                                }else if(response.data[1][i].class_type == 2){
                                    class_type = `<span style="color:black" class="" >Thực hành</span> <br>`
                                }else{
                                    class_type = `<span style="color:black" class="" >Lý thuyết + Thực hành</span> <br>`
                                }
                                if(response.data[1][i].start_date != null){
                                    datetime = `<span style="color:black" class="" >${response.data[1][i].start_date} đến ${response.data[1][i].end_date}</span> <br>`
                                }
                                $('#class-info')
                                    .append(`
                                    <input type="hidden" class="input_class_id" id='input_class_id_${i}' value="${response.data[1][i].id}"/>
                                    Name: <span style="color:black" class="" >${response.data[1][i].name}</span> <br>
                                    Loại lớp: ${class_mode}
                                    Kiểu lớp: ${class_type}
                                    Thời gian: ${datetime} <br><br>
                                   `);
                            }
                        }
                    },
                    error: function (response) {

                    }
                });
            }
            function loadInfo2(){
                $.ajax({
                    url: "{{route('api.division.info2')}}",
                    type: 'GET',
                    dataType: 'json',
                    data: $('#form-filter2').serialize(),
                    processData: false,
                    contentType: false,
                    async: true,
                    cache: false,
                    enctype: 'multipart/form-data',
                    success: function (response) {
                        $('#teacher-info2,#class-info2').html('');
                        let gender = ``;
                        if(response.data[0] != null) {
                            if (response.data[0].gender === 1) {
                                gender = `<i style="font-Size:16px;color:blue" class="mdi mdi-gender-male"></i>
                                                <br>`;
                            } else if (response.data[0].gender === 2) {
                                gender = `<i style="font-Size:16px;color:hotpink" class="mdi mdi-gender-female"></i>
                                                <br>`
                            } else if (response.data[0].gender === 3) {
                                gender = ` <i style="font-Size:16px;color:green" class="mdi mdi-gender-male-female"></i>
                                                <br>`
                            }
                            $('#teacher-info2')
                                .append(`
                                <input type="hidden" value="${response.data[0].id}"/>
                                Name: <span style="color:black" class="" >${response.data[0].first_name} ${response.data[0].last_name} </span> ${gender}
                                <span> Mail: </span>
                                <a href="mailto::${response.data[0].email}">
                                ${response.data[0].email}
                                </a>
                                <br>
                                Address:
                                <span style="color:black" class="" >${response.data[0].address}</span>
                               `);
                        }
                        if(response.data[1] != null) {
                            let classs= ``;
                            for (const [key, value] of Object.entries(response.data[1])) {
                                classs += `<span style="color:black" class="" >- ${value}</span> <br>`
                            }
                            $('#class-info2')
                                .append(`
                                    ${classs}
                               `);
                        }
                    },
                    error: function (response) {

                    }
                });
            }
            function unsetDivision(teacherId,classId){
                $.ajax({
                    url: "{{route('api.division.unset')}}",
                    type: 'GET',
                    data: {
                        teacherId: teacherId,
                        classId: classId
                    },
                    success: function (response) {
                        $.toast(
                            {
                                heading: 'Thông báo',
                                text: 'Hủy thành công',
                                showHideTransition: 'slide',
                                icon: 'success',
                                position: 'top-right',
                                hideAfter: 5000
                            }
                        );
                        $('#btn-edit-division').click();
                    },
                    error: function (response) {
                        $.toast(
                            {
                                heading: 'Lỗi',
                                text: 'Có lỗi xảy ra',
                                showHideTransition: 'slide',
                                icon: 'error',
                                position: 'top-right',
                                hideAfter: 5000
                            }
                        );
                    }
                });
            }
        </script>
    @endpush
    <div id="modal-edit-division" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Edit division</h5>
                    <button type="button" onclick="hidemodel('modal-edit-division')" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                        <tr>
                            <th>Thông tin giáo viên</th>
                            <th>Lớp học phân công  </th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody id="modal-table-edit-vision">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection()
