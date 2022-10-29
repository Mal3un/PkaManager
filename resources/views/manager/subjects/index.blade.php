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
                                <label for="select-major">Ngành</label>
                                <select class="custom-select select-filter-major" id="select-major" name="major" >
                                    <option selected>All...</option>
                                    @foreach($majors as $major )
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
                                    @foreach($subjects as $subject )
                                        <option value="{{ $subject->id }}"
                                                @if ((string)$subject->id === $selectedSubject) selected @endif>
                                            {{$subject->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(Auth::user()->role_id === 3)
                            <div class="float-right col">
                                <a href="" id="btn-create-classe" class="btn btn-success float-right">
                                    Tạo môn học
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
                            <th>Tên môn học</th>
                            <th>Thông tin môn học</th>
                            <th>Chuyên ngành</th>
                            @if(Auth::user()->role_id === 3)
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
                                <td style="">
                                    <span style="color:black">{{$each->name}}</span>
                                </td>
                                <td>
                                    Số tín chỉ:
                                    <span style="color:black">{{$each->number_credits}} tín chỉ </span>
                                    <br>
                                    Loại môn học:
                                    <span style="color:black">{{$each->study_typename}} </span>
                                </td>
                                <td>
                                    <span style="color:black">{{$each->major_name}}  </span>
                                </td>
                                @if(Auth::user()->role_id === 3)
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
            $('#select-major').select2();
            $('#select-subject').select2();

            $('#modal-major').select2();
            $('#modal-subject-type').select2();
            // $('#number_credits').select2();
            $(document).ready(async function() {
                $('.select-filter-subject,.select-filter-major').change(function(){
                    $('#form-filter').submit();
                });
            });
            $('#btn-create-classe').click(async function(e){
                await e.preventDefault();
                await $('#modal-create-classe').modal('show');
            });


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
            function hidemodel(idName){
                $('#'+ idName).hide();
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
                                <label for="modal-subject-type">Loại môn học</label>
                                <select class="custom-select " id="modal-subject-type"  name="study_type" >
                                    @foreach($subject_types as $subject_type => $value)
                                        <option value="{{ $subject_type }}">
                                            {{$value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-flex mb-3">
                            <div class="col-md-6 ">
                                <label for="modal-name-subject">Tên môn học </label>
                                <input  class="form-control " id="modal-name-subject"  type="text"  name="name">
                            </div>
                            <div class="col-md-2 ">
                                <label for="number_credits">Số tín </label>
                                <input  class="form-control " id="number_credits"  type="number"  name="number_credits">
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
