@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <div class="card-body">
                    <form action="{{route('manager.courses.update',$data)}}" id="form-update-course" method="post" class="d-flex flex-column">
                        @csrf
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-4  ">
                                <label for="courseName">Khóa học</label>
                                <input type="text" value="{{$data->name}}" name="name" value="" id="courseName" class="form-control"/>
                            </div>
                            <div class="col-md-4  ">
                                <label for="courseName">Thời gian bắt đầu</label>
                                <input type="date" name="duration"  value="{{$data->duration}}" id="courseName" class="form-control"/>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary w-15" id="btn-update-course" >Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
        <script>
            $(document).ready(function() {
                $('#btn-update-course').click(function (event){
                    event.preventDefault();
                    $.ajax({
                        url: '{{route('manager.courses.update',$data)}}',
                        type: 'post',
                        data: $('#form-update-course').serialize(),
                        enctype: 'multipart/form-data',
                        success: function (response) {
                            $.toast({
                                heading: 'Success !',
                                text: `Your course have been updated.`,
                                showHideTransition: 'fade',
                                icon: 'success',
                                hideAfter: 5000,
                                position: 'top-right',
                            })
                            window.setTimeout(function(){
                                window.location.href = "{{route('manager.courses.index')}}";
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
            });
        </script>
    @endpush
@endsection()
