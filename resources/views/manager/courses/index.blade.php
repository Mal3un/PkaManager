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
                                <div class="input-group mb-3 w-25 mr-3">
                                    <label for="select-course">Khóa học</label>
                                    <select class="custom-select select-filter-role" id="select-course" name="courseName" >
                                        <option selected>All...</option>
                                        @foreach($courseNames as $courseName => $value)
                                            <option value="{{ $value }}"
                                                    @if ((string)$value === $selectedCourse) selected @endif>
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(Auth::user()->role_id === 3)
                                    <div class="float-right col">
                                        <a href="" id="btn-create-course" class="btn btn-success float-right">
                                            Thêm khóa học
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
                            <th>Tên Khóa</th>
                            <th>Mã Khóa</th>
                            <th>Thời gian bắt đầu </th>
                            @if(Auth::user()->role_id === 3)
                                <th style="width:10%">Sửa</th>
                                <th style="width:10%">Xóa</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $each)
                            <tr>
                                <td>
                                    <a href="">
                                        Khóa {{ substr($each->name,1)}}
                                    </a>
                                </td>
                                <td>
                                    {{ $each->name }}
                                </td>
                                <td>
                                    {{ $each->duration }}
                                </td>
                                @if(Auth::user()->role_id ===3)
                                <td>
                                    <a href='{{route("manager.$table.edit",$each)}}' id="btn-edit-course" class="btn btn-primary">
                                        <i>Edit</i>
                                    </a>
                                </td>
                                <td>
                                    <form method="post" action='{{route("manager.$table.destroy",$each)}}'>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
    <script>
        $('#select-course').select2();
        $(document).ready(function() {
            $('.select-filter-role').change(function(){
                $('#form-filter').submit();
            });
            $('#btn-create-course').click(function (event){
                event.preventDefault();
                $('#modal-create-course').show();
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
        }
    </script>
@endpush
<div id="modal-create-course" class="modal" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,0.5)">
    <div class="modal-dialog " role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Create {{$table}}</h5>
                <button type="button" onclick="hidemodel('modal-create-course')" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-create-course" action="{{route('manager.courses.store')}}" class="d-flex flex-column " method="POST">
                    @csrf
                    <div class="form-group d-flex mb-3">
                        <div class="col-md-12  ">
                            <label for="courseName">Khóa</label>
                            <input  name="name" id="courseName" type="text" class="form-control" placeholder="khóa học">
                        </div>
                    </div>
                    <div class="form-group d-flex mb-3">
                        <div class="col-md-12  ">
                            <label for="duration">Thời gian bắt đầu</label>
                            <input  name="duration" id="duration" type="date" class="form-control" placeholder="năm">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="submitForm('course','modal-create-course')" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection()
