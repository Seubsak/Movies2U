@extends('layouts.navbar')
@section('content')
    <div class="container">
        <h2 class="movieslist mt-5" style="border-left: 4px solid red;">Movies List</h2>
        <div class="row mt-4">
            @foreach ($moviesInWatchlist as $miw)
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="movie-poster">
                        <a></a><img src="{{ asset('Materials/Movies/' . $miw->movie_id . '.png') }}" class="card-img-top" >
                        </a>
                        <div class="button-overlay">
                            @if ( Auth::user()->roles == 1)
                            <a href="/moviedetail/{{ $miw->movie_id }}" class="btn detail-btn-primary mt-1" color="black">Details</a>
                            <a href="/watchlist/delete/{{ $miw->movie_id }}" class="btn delete-btn-primary mt-1" onclick="return confirm('Are you sure you want to delete this movie?')">Remove</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body mt-2">
                        <h4 class="movie-title">{{ $miw->Movies_name }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
