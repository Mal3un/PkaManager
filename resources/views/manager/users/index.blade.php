@extends('layout.master')
@section('content')
    <div class="row ">
        <div class="col-xl-12 col-lg-7 center">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#aboutme" data-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                                Thông tin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                Đổi mật khẩu
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="aboutme">

                            <div class="card text-center">
                                <div class="card-body">
                                    <img src="{{asset(Auth::user()->avatar)}}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                    <h4 class="mb-0 mt-2">{{$info['name']}}</h4>
                                    <p class="text-muted font-14">{{$info['role']}}</p>

{{--                                    <button type="button" class="btn btn-success btn-sm mb-2">Follow</button>--}}
{{--                                    <button type="button" class="btn btn-danger btn-sm mb-2">Message</button>--}}

                                    <div class="text-center mt-3 " style="border-top:1px solid #ccc;padding-top:20px">
                                        <p class="text-muted mb-2 font-13"><strong>Họ tên :</strong> <span class="ml-2">{{$info['name']}}</span></p>

                                        <p class="text-muted mb-2 font-13"><strong>Ngày sinh :</strong><span class="ml-2">{{$info['birthdate']}}</span></p>

                                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{$info['email']}}</span></p>

                                        <p class="text-muted mb-1 font-13"><strong>Địa chỉ :</strong> <span class="ml-2">{{$info['address']}}</span></p>
                                    </div>
                                </div> <!-- end card-body -->
                            </div>

                        </div> <!-- end tab-pane -->
                        <div class="tab-pane" id="settings">
                            <form>
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Thay đổi mật khẩu</h5>
                                <div class="text-center d-flex flex-column center-flex align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="oldPass" name="oldPass" placeholder="Nhập mật khẩu hiện tại">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-merge">
                                                <input type="password" class="form-control" id="newPass" name="newPass" placeholder="Nhập mật khẩu mới">
                                                <div class="input-group-append" data-password="false">
                                                    <div class="input-group-text">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="newPass2" name="newPass2" placeholder="Nhập lại mật khẩu mới">
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                                <div class="text-right">
                                    <button type="submit" id="btn-sumit-chage-password" class="btn btn-success mt-2"><i class="mdi mdi-content-save"></i> Thay đổi mật khẩu</button>
                                </div>
                            </form>
                        </div>
                        <!-- end settings content-->

                    </div> <!-- end tab-content -->
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div>
    </div>
    @push('js')
    <script>
        $(document).ready(function() {
            $('#btn-sumit-chage-password').click(function (e) {
                e.preventDefault();
                let oldPass = $('#oldPass').val();
                let newPass = $('#newPass').val();
                let newPass2 = $('#newPass2').val();
                if (newPass != newPass2){
                    $.toast({
                        heading: 'Lỗi',
                        text: 'Mật khẩu mới không khớp',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }else if(oldPass == newPass) {
                    $.toast({
                        heading: 'Lỗi',
                        text: 'Mật khẩu mới không được giống mật khẩu cũ',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }else{
                    $.ajax({
                        url: "{{route('manager.users.changePassword')}}",
                        type: "POST",
                        data: {
                            oldPass: oldPass,
                            newPass: newPass,
                            newPass2: newPass2,
                        },
                        success: function (data) {
                            $.toast({
                                heading: 'Thành công',
                                text: 'Thay đổi mật khẩu thành công',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3500,
                                stack: 6
                            });
                            window.location.href = "{{route('manager.users.index')}}";

                        },
                        error: function (data) {
                            $.toast({
                                heading: 'Thất bại',
                                text: 'Mật khẩu cũ không đúng !',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3500,
                                stack: 6
                            });
                        }

                    });
                }
            });

        });
    </script>
    @endpush

@endsection()
