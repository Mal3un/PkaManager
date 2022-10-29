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
                        <div class="form-group d-flex justify-content-between">
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-post">Bài đăng </label>
                                <select class="custom-select select-filter-role" id="select-post" name="select-post" >
                                    <option selected>All...</option>
                                    @foreach($data as $each )
                                        <option value="{{ $each->title }}"
                                                @if ((string)$each->title === $selectedPost) selected @endif>
                                            {{$each->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(Auth::user()->role_id === 3 || Auth::user()->role_id === 2 )
                            <div class="input-group mb-3 w-25 mr-3">
                                <label for="select-status">Trạng thái </label>
                                <select class="custom-select select-filter-role" id="select-status" name="select-status" >
                                    <option selected>All...</option>
                                    @foreach($status as $each => $value)
                                        <option value="{{ $value }}"
                                                @if ((string)$value === $selectedStatus) selected @endif>
                                            {{$each }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="float-right">
                                <a href="{{route('manager.posts.create')}}" id="btn-create-course" class="btn btn-success float-right">
                                    Tạo bài viết mới
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-centered mb-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Post</th>
                            @if(Auth::user()->role_id === 3)
                                <th style="width:10%">Edit</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $each)
                            <tr>
                                <td>
                                    <a href="">
                                        {{ $each->id }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('manager.posts.detail',$each->id)}}">
                                        <h6 style="text-decoration: none;color:rgba(0,0,0,0.6);float:left;text-align:left;width:100%">{{$each->getDateConverted()}}, {{$each->getTimeConverted()}}</h6>
                                        <img alt="post" width="200px" src="{{asset($each->image )}}"><br><br>
                                        {{ $each->title }}
                                    </a>
                                </td>
                                @if(Auth::user()->role_id === 3)
                                <td>
                                    @if($each->status === 0)
                                        <div class="d-flex justify-content-lg-center align-center mb-3">
                                                <a href="{{route('manager.posts.publicPost',$each->id)}}" class="btn btn-success">Duyệt</a>
                                        </div>
                                    @endif
                                    <div class="d-flex w-100" style="border:1px solid #ccc;padding:10px">
                                        <a style="margin-right:10px" href='' id="btn-edit-course" class="btn btn-primary">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form method="post" action=''>
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" name="delete" class="btn btn-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
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
            $('#select-post').select2();
            $('#select-status').select2();
            $(document).ready(function() {
                $('.select-filter-role').change(function(){
                    $('#form-filter').submit();
                });
            });
        </script>
    @endpush
@endsection()
