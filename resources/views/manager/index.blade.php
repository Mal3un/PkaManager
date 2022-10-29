@extends('layout.master')
@section('content')
    <div class="row">
        @foreach($post as $key => $value)
            @if($key === 0 || $key % 3 === 0)
                <div class="col-xl-4 col-lg-12 order-lg-2 order-xl-1">
                    <div class="card">
                        <a href="{{route('manager.posts.detail',$value->id)}}" methods="get" class="card-body text-center" style="position: relative;">
                            <img class="image-preview" style="width: 100%" alt="ảnh" src="{{asset($value->image)}}">
                            <h4 style="color:black; font-weight:bold;" class="title-preview">{{$value->title}}</h4>
                            <h6 style="text-decoration: none;color:rgba(0,0,0,0.6);float:right;text-align:right">{{$value->getDateConverted()}}, {{$value->getTimeConverted()}}</h6>
                        </a>
                    </div> <!-- end card-->

                </div>
            @endif
            @if($key !== 0 && $key % 3 !== 0)
                <div class="col-xl-4 col-lg-6 order-lg-1">
                    <div class="card">
                        <a href="{{route('manager.posts.detail',$value->id)}}" methods="get" class="card-body text-center" style="position: relative;">
                            <img class="image-preview" style="width: 100%;" alt="ảnh" src="{{asset($value->image)}}">
                            <h4 style="color:black; font-weight:bold;" class="title-preview">{{$value->title}}</h4>
                            <h6 style="text-decoration: none;color:rgba(0,0,0,0.6);float:right;text-align:right">{{$value->getDateConverted()}}, {{$value->getTimeConverted()}}</h6>
                        </a>
                    </div> <!-- end card-->
                </div>
                @endif
        @endforeach
    </div>
    <div class="w-100 d-flex justify-content-lg-center mt-2 mb-4">
        <a href="{{route('manager.posts.index')}}" class="btn btn-primary">Xem tất cả tin tức</a>
    </div>
@endsection()
