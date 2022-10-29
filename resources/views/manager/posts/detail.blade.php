@extends('layout.master')
@push('css')
    <link href="{{asset('css/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header " >
                    <h4 style="text-decoration: none;color:rgba(0,0,0,0.6);float:right;text-align:right">{{$data->getDateConverted()}}, {{$data->getTimeConverted()}}</h4>
                </div>
                <div class="card-body ">
                    <div class="row d-flex justify-content-sm-around">
                        <div class="col-xl-12 col-lg-12 order-lg-2 order-xl-1">
                            <div class="card">
                                <div class="card-body text-center" style="position: relative;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                    <h3 style="color:black; font-weight:bold;margin-bottom:24px;width:60%" class="title-preview">{{$data->title}}</h3>
                                    <img src="{{asset($data->image)}}" class="image-preview" style="width:66%" alt="ảnh">
                                    <br>
                                    <div class="content-preview" style="width:50%;text-align:justify">
                                        {!! $data->content !!}
                                    </div>
                                </div>
                            </div> <!-- end card-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="{{asset('js/summernote-bs4.min.js')}}"></script>
    @endpush

@endsection()
