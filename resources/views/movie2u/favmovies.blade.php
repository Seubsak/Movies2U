@extends('layouts.navbar')

@section('content')
    <div class="container">
        <h2 class="movieslist mt-5" style="border-left: 4px solid red;">My Favorite</h2>
        <div class="row mt-4">
            @php
                $displayedMovies = []; // สร้างตัวแปรเพื่อเก็บหนังที่เคยแสดงแล้ว
            @endphp

            @foreach ($favoriteMovies as $miw)
                @if (!in_array($miw->movie_id, $displayedMovies))
                    @if ($miw->user_id == Auth::user()->id)
                        <div class="col-xl-2 col-md-4 mb-4">
                            <div class="movie-poster">
                                <a></a><img src="{{ asset('Materials/Movies/' . $miw->movie_id . '.png') }}" class="card-img-top">
                                </a>
                                <div class="card-body mt-2">
                                    @foreach ($totalLikesByMovie as $likes)
                                        @if ($likes->movie_id == $miw->movie_id)
                                            
                                        @endif
                                    @endforeach
                                </div>
                                <div class="button-overlay">
                                    @if (Auth::user()->roles == 1)
                                        <a href="/delfav/{{ $miw->movie_id }}" class="btn delete-btn-primary mt-1"
                                            onclick="return confirm('Are you sure you want to unlike this movie?')">Unlike</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @php
                            $displayedMovies[] = $miw->movie_id; // เพิ่ม movie_id ลงในรายการหนังที่เคยแสดงแล้ว
                        @endphp
                    @endif
                @endif
            @endforeach
        </div>
    </div>
@endsection
