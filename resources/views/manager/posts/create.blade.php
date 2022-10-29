@extends('layout.master')
@push('css')
    <link href="{{asset('css/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header " >

                </div>
                <form id="form_create_post" method="post" action="{{route('manager.posts.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row">
                        <div class="col-8">
                            <label for="title">Tiêu đề chính</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Tiêu đề">
                        </div>
                        <div class="form-group col-2">
                            <label for="image">Ảnh tiêu đề</label>
                            <input type="file" alt="ảnh" accept="image/png, image/jpeg, image/gif" onchange="chooseImage(this)" class="form-control-file" id="image" name="image">
                        </div>
                        <div class="form-group col-2">
                            <img style="border: 1px solid #ccc;margin-right:10px" id="image-show" width="100" height="100" >
                            <span id="btn-cancel-image" class="btn btn-sm btn-danger" for="image-show">Hủy</span>
                        </div>
                        <div class="col-12 mt-4">
                            <label for="summernote">Nội dung</label>
                            <textarea id="summernote" name="content"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="button-review-post" class="btn btn-primary">Xem thử</button>
                        <button type="submit" id="button-store-post" class="btn btn-success">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('js')
        <script src="{{asset('js/summernote-bs4.min.js')}}"></script>
        <script>
            function chooseImage(fileInput){
                if(fileInput.files && fileInput.files[0]){
                    let file = fileInput.files[0];
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-show').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            }
            $(document).ready(function() {
                $('#summernote').summernote({
                    placeholder: 'Nội dung bài viết',
                    tabsize: 2,
                    height: 300,
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onImageUpload: function (files) {
                            console.log(files);
                            if (!files.length) return;
                            let file = files[0];
                            // create FileReader
                            let reader = new FileReader();
                            reader.onloadend = function () {
                                // when loaded file, img's src set datauri
                                var img = $("<img>").attr({src: reader.result, width: "100%"}); // << Add here img attributes !
                                $('#summernote').summernote("insertNode", img[0]);
                            }

                            if (file) {
                                reader.readAsDataURL(file);
                            }

                        },
                        onImageLinkInsert: function(url) {
                            let img = $('<img>').attr({ src: url , width: "100%"})
                            $('#summernote').summernote("insertNode", img[0]);
                        }
                    }
                })
                $('#button-store-post').click(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: '{{route('manager.posts.store')}}',
                        type: 'POST',
                        data: new FormData($('#form_create_post')[0]),
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            $.toast({
                                heading: 'Thành công',
                                text: 'Đã lưu bài viết',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3500,
                                stack: 6
                            });
                            setTimeout(function () {
                                window.location.href = '{{route('manager.posts.index')}}';
                            }, 3000);
                        },
                        error: function (data) {
                            $.toast({
                                heading: 'Lỗi',
                                text: 'Đã có lỗi xảy ra',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3500,
                                stack: 6
                            });
                        }
                    })
                })

                $('#btn-cancel-image').click(function () {
                    $('#image-show').attr('src', '#');
                });

                $('#button-review-post').click(function (e) {
                    e.preventDefault();
                    let title = $('#title').val();
                    let content = $('#summernote').summernote('code');
                    let image = $('#image-show').attr('src');
                    $('#modal-preview-post').modal('show');
                    $('.title-preview').text(title);
                    $('.content-preview').html(content);
                    $('.image-preview').attr('src', image);
                });
            });


        </script>
    @endpush
    <div id="modal-preview-post" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-full-width " role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Xem trước</h5>
                    <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex justify-content-sm-around">
                        <div class="col-xl-3 col-lg-6 order-lg-1">
                            <div class="card">
                                <div class="card-body text-center" style="position: relative;">
                                    <img class="image-preview" width="auto" style="width: 100%" alt="ảnh">
                                    <h4 style="color:black; font-weight:bold;" class="title-preview"></h4>
                                    <h6 style="text-decoration: none;color:rgba(0,0,0,0.6);float:right;text-align:right">ngày..., giờ...</h6>
                                </div>
                            </div> <!-- end card-->
                        </div>
                        <div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">
                            <div class="card">
                                <div class="card-body text-center" style="position: relative;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                    <h6 style="text-decoration: none;color:rgba(0,0,0,0.6);float:right;text-align:right">ngày... , giờ...</h6>
                                    <h3 style="color:black; font-weight:bold;margin-bottom:10px" class="title-preview"></h3>
                                    <img class="image-preview" width="auto" style="width: 100%" alt="ảnh">
                                    <br>
                                    <div class="content-preview" style="width:70%;text-align:justify"></div>
                                </div>
                            </div> <!-- end card-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="submitForm('course','modal-preview-post')" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

@endsection()
