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
                                <label for="select-teacher">Giáo viên</label>
                                <select class="custom-select select-filter-teacher" id="select-teacher" name="teachers" >
                                    <option selected>All...</option>
                                    @foreach($teachers as $teacher )
                                        <option value="{{ $teacher->first_name}} {{$teacher->last_name}}"
                                                @if ((string) ($teacher->first_name . " " . $teacher->last_name) === $selectedTeacher) selected @endif>
                                            {{$teacher->first_name}} {{$teacher->last_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="float-right col">
                                <a href="" id="btn-create-classe" class="btn btn-success float-right">
                                    Tạo giáo viên
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thông tin giáo viên</th>
                            <th style="width:10%">Sửa</th>
                            <th style="width:10%">Xóa</th>
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
                                    Name:
                                    <span style="color:black">{{$each->first_name}} {{$each->last_name}}</span>
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
                                    Ngày sinh:
                                    <span style="color:black">{{date("d/m/Y",strtotime($each->birthdate))}}</span>
                                    <br>
                                    <span >Mail:</span>
                                    <a href="mailto::{{$each->email}}">
                                        {{$each->email}}
                                    </a>
                                    <br>
                                    <span >Address:</span>
                                    <span style="color:black">{{$each->address}}</span>
                                </td>
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
            $('#select-teacher').select2();

            $(document).ready(async function() {
                $('.select-filter-teacher').change(function(){
                    $('#form-filter').submit();
                });
            });
        </script>
    @endpush
@endsection()
