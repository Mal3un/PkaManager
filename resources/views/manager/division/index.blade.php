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
                                <label for="select-teacher">Tên giáo viên</label>
                                <select class="custom-select select-filter-role" id="select-teacher" name="teacher" >
                                    <option selected>All...</option>
                                    @foreach($data as $each)
                                        <option value="{{ $each->id }}"
                                                @if ((string)$each->id === $selectedTeacher) selected @endif>
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
                            <th>#</th>
                            <th>Tên giáo viên</th>
                            <th>Dach sách phân công  </th>
                            {{--                            <th style="width:10%">Sửa</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $each)
                            <tr>
                                <td>
                                    <a href="">
                                        GV0{{$each->id}}
                                    </a>
                                </td>
                                <td>
                                    {{$each->first_name}} {{$each->last_name}}
                                </td>
                                <td>
                                    @foreach ($classes as $class)
                                        @if ($class->teacher_id === $each->id)
                                            - <span style="color:#1c4783">{{$class->name}}</span>  @if($class->start_date !== null ||$class->end_date !== null )| {{date("d/m/Y",strtotime($class->start_date))}} - {{date("d/m/Y",strtotime($class->end_date))}}@endif
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                {{--                                <td>--}}
                                {{--                                    <a href='' id="btn-edit-course" class="btn btn-primary">--}}
                                {{--                                        <i>Edit</i>--}}
                                {{--                                    </a>--}}
                                {{--                                </td>--}}

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
            $('#select-teacher').select2();
            $(document).ready(function() {
                $('.select-filter-role').change(function(){
                    $('#form-filter').submit();
                });
            });
        </script>
    @endpush
@endsection()
