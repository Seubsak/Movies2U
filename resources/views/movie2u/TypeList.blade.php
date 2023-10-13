@extends('layouts.navbar')
@section('content')
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

@if(session('alert'))
    <script>
        alert("{{ session('alert') }}");
    </script>
@endif
<div class="container-fluid">
    <p class="title_category_Movies mt-3">Category Movies</p>
    <div class="row">
        <div class="col-2 mt-4">
            <header>
                <h2 class="type">Type</h2>
            </header>
        </div>
        <div class="col-10 mt-4">  <!-- โชว์หัวข้อประเภทหนังที่เลือก -->
            @foreach ($mtype as $mt)
            <header>
                <h2 class="type">{{ $mt->type_name }}</h2>
            </header>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <ul class="nav nav-link"> <!-- วนลูปนำหัวข้อประเภทหนังมาโชว์ -->
                @foreach($type as $t)
                <li class="mt-3">
                    <p class="title_list_type"><i class="bi bi-caret-right-fill text-black"></i><a class="list_type" aria-current="page" href="/type/{{ $t->type_id }}"> {{ $t->type_name }}</a></p>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="col-10">
            <div class="row">  <!-- วนลูปนำหนังในหัวข้อที่เลือกมาโชว์ทั้งหมด -->
                @foreach ($mtype as $mt)
                @php
                    $foundMovies = false;
                @endphp
                    @foreach ($movie as $m)
                    @if($mt->type_id == $m->movie_type_id)
                    <div class="col-3">
                        <div class="card mt-4" style="width: auto">
                            <a href="/moviedetail/{{ $m->movie_id }}">
                                <img class="card-img-type" src="{{ asset('Materials/Movies/' . $m->movie_id . '.png') }}" alt="Movie poster" width="150px" height="350px"/>
                                <a href="/addfav/{{ $m->movie_id }}" class="btn btn-link"><i class="bi bi-heart text-danger"></i>
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $m->movie_name }}
                                </h5>
                                <div class="mt-3">
                                    <i class="bi bi-star-fill text-warning"><b class="text-black"> {{ $m->movie_score }} </b></i>
                                </div>
                                <div class="mt-3">
                                    @guest
                                    <!-- ระดับผู้ที่ยังไม่ได้login -->
                                    <a href="/moviedetail/{{ $m->movie_id }}" class="btn btn-warning" style="width: 100%;">Detail</a>
                                    <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark mt-2" style="width: 100%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                    @else
                                    <!-- ระดับ user -->
                                    @if( Auth::user()->roles  == 1)
                                    <a href="/moviedetail/{{ $m->movie_id }}" class="btn btn-warning" style="width: 100%;">Detail</a>
                                    <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark mt-2" style="width: 100%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                    <!-- ระดับ admin -->
                                    @elseif ( Auth::user()->roles  == 2 )
                                    <a href="/moviemanagement/editForm/{{ $m->movie_id }}" class="btn btn-warning" style="width: 100%;">Edit</a>
                                    <a href="/moviemanagement/delete/{{ $m->movie_id }}" class="btn btn-danger mt-2" style="width: 100%;" onclick="return confirm('Are you sure you want to delete this movie?')">Delete</a>
                                    @endif
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                        @php
                            $foundMovies = true;
                        @endphp
                    @endif
                    @endforeach
                    @if (!$foundMovies) <!-- หากเลือกประเภทหนังแล้วไม่มีข้อมูลในนั้น -->
                    <div class="col-12 mt-4">
                        <div class="alert alert-dark text-center" role="alert">
                            Not found movies in this type.
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
