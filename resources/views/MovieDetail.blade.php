@extends('layouts.navbar')
@section('content')
    <div class="container-fluid">
        @foreach ($reviews as $rv)
                    <div class="modal fade" id="editModal_{{ $rv->id }}" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Post</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="{{ route('reviewUpdate') }}">
                                @csrf
                                <div class="mb-3">
                                  <textarea name="info" class="form-control" id="message-text">{{ $rv->review_info }}</textarea>
                                  <input type="hidden" value="{{ $rv->id }}" name="id">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-outline-primary">Save</button>
                            </div>
                        </form>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    
         @foreach ($replies as $rp)
                    <div class="modal fade" id="editModal_{{ $rp->id }}" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Edit reply</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="{{ route('replyUpdate') }}">
                                @csrf
                                <div class="mb-3">
                                  <textarea name="info" class="form-control" id="message-text">{{ $rp->reply_info }}</textarea>
                                  <input type="hidden" value="{{ $rp->id }}" name="id">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-outline-primary">Save</button>
                            </div>
                        </form>
                          </div>
                        </div>
                      </div>
                    @endforeach
        <div class="row">
            <div class="col-4">
                @foreach($movie as $m)
                <h1>{{ $m->movie_name }}</h1>
                <p><a href="">{{ $m->movie_year_on_air }}</a>-
                @foreach ($ctr as $r)
                    @if ($m->critical_rate == $r->ctr_id)
                    <a href="">{{ $r->ctr_name }}</a>
                    @endif
                @endforeach
                -{{ floor($m->movie_time/60) }}h {{ floor($m->movie_time%60) }}m</p>
                <h5>Movie Score</h5>
                <h6><i class="bi bi-star-fill text-warning"> </i>{{ $m->movie_score }}/10</h6>
                @endforeach
            </div>
            <div class="col-8">
                @foreach ($movie as $m )
                <video style="max-width: 850px" controls>
                    <source src="{{ asset('Materials/Movies/' . $m->movie_id . '.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                @endforeach
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-4">
                @foreach($movie as $m)
                <img src="{{ asset('Materials/Movies/' . $m->movie_id . '.png') }}" alt="Movie poster" style="max-width: 350px"/>
                @endforeach
            </div>
            <div class="col-8">
                @foreach($movie as $m)
                <br>
                @foreach ($mtype as $mt)
                    @if($m->movie_type_id == $mt->type_id)
                        <p id="type_movie"><a href="/type/{{ $mt->type_id }}"><span id="type_mv">{{ $mt->type_name }}</span></a></p>
                    @endif
                @endforeach
                <h4>Movie info</h4>
                <p>{{ $m->movie_info }}</p>
                <p>
                    Director:
                    @foreach ($detail as $d)
                    @foreach ($emp as $e)
                        @foreach ($empt as $et)
                            @if($d->movie_id == $m->movie_id && $d->emp_id == $e->emp_id && $d->emp_type_id == $et->emp_type_id)
                                @if($et->emp_type_name == 'Director')
                                    {{ $e->emp_name }}
                                @endif
                            @endif
                        @endforeach
                     @endforeach
                     @endforeach
                </p>
                <p>
                    Writer:
                    @foreach ($detail as $d)
                    @foreach ($emp as $e)
                        @foreach ($empt as $et)
                            @if($d->movie_id == $m->movie_id && $d->emp_id == $e->emp_id && $d->emp_type_id == $et->emp_type_id)
                                @if($et->emp_type_name == 'Writer')
                                    {{ $e->emp_name }}
                                @endif
                            @endif
                        @endforeach
                     @endforeach
                     @endforeach
                </p>
                <p>
                    Actor:
                    @foreach ($detail as $d)
                    @foreach ($emp as $e)
                        @foreach ($empt as $et)
                            @if($d->movie_id == $m->movie_id && $d->emp_id == $e->emp_id && $d->emp_type_id == $et->emp_type_id)
                                @if($et->emp_type_name == 'Actor')
                                    {{ $e->emp_name }}
                                @endif
                            @endif
                        @endforeach
                     @endforeach
                     @endforeach
                </p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="h2_top10">More like this</h2>
                <div class="row">
                    @php
                    $count = 0;
                    $foundMovies = false;
                    @endphp
                    @foreach ($allmovie as $m)
                        @foreach ($movie as $am)
                            @foreach ($mtype as $mt)
                                @if ($am->movie_type_id == $mt->type_id && $am->movie_type_id == $m->movie_type_id && $am->movie_name != $m->movie_name)
                                    @php
                                    $foundMovies = true;
                                    @endphp
                                    @if ($count < 4 && $foundMovies) {{-- ตรวจสอบว่ายังไม่เกิน 4 เรื่อง --}}
                                    <div class="col-3">
                                        <div class="card mt-3" style="width: auto">
                                            <a href="/moviedetail/{{ $m->movie_id }}">
                                                <img class="card-img" src="{{ asset('Materials/Movies/' . $m->movie_id . '.png') }}" alt="Movie poster" width="300px" height="450px"/>
                                                <a href="/addfav/{{ $m->movie_id }}" class="btn btn-link"><i class="bi bi-heart text-danger"></i>
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title  d-flex justify-content-between align-items-center">
                                                    <b>{{ $m->movie_name }}</b>
                                                    <i class="bi bi-star-fill text-warning"><b class="text-black"> {{ $m->movie_score }} </b></i>
                                                </h5>
                                                @guest
                                                <div class="d-flex justify-content-between align-items-center mt-4">
                                                    <a href="{{ url('/moviedetail/'.$m->movie_id) }}" class="btn btn-warning" style="width: 48%;">Detail</a>
                                                    <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark" style="width: 48%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                                </div>
                                                @else
                                                <div class="d-flex justify-content-between align-items-center mt-4">
                                                    @if( Auth::user()->roles  == 1)
                                                    <a href="{{ url('/moviedetail/'.$m->movie_id) }}" class="btn btn-warning" style="width: 48%;">Detail</a>
                                                    <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark" style="width: 48%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                                    @elseif ( Auth::user()->roles  == 2 )
                                                    <a href="/moviemanagement/editForm/{{ $m->movie_id }}" class="btn btn-warning" style="width: 48%;">Edit</a>
                                                    <a href="/moviemanagement/delete/{{ $m->movie_id }}" class="btn btn-danger" style="width: 48%;" onclick="return confirm('Are you sure you want to delete this movie?')">Delete</a>
                                                    @else
                                                    <a href="{{ url('/moviedetail/'.$m->movie_id) }}" class "btn btn-warning" style="width: 48%;">Detail</a>
                                                    <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark" style="width: 48%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                                    @endif
                                                </div>
                                                @endguest
                                            </div>
                                        </div>
                                    </div>
                                        @php
                                        $count++;
                                        @endphp {{-- เพิ่มตัวนับเมื่อแสดงเรื่อง 1 เรื่อง --}}
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach

                    {{-- เมื่อไม่พบหนังที่มีประเภทเดียวกันให้แสดงหนังที่เหลือทั้งหมด --}}
                    @if ($count < 4 && !$foundMovies)
                        @foreach ($allmovie as $m)
                            @if ($count < 4)
                            <div class="col-3">
                                <div class="card mt-3" style="width: auto">
                                    <a href="/moviedetail/{{ $m->movie_id }}">
                                        <img class="card-img" src="{{ asset('Materials/Movies/' . $m->movie_id . '.png') }}" alt="Movie poster" width="300px" height="450px"/>
                                        <a href="/addfav/{{ $m->movie_id }}" class="btn btn-link"><i class="bi bi-heart text-danger"></i>
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title  d-flex justify-content-between align-items-center">
                                            <b>{{ $m->movie_name }}</b>
                                            <i class="bi bi-star-fill text-warning"><b class="text-black"> {{ $m->movie_score }} </b></i>
                                        </h5>
                                        @guest
                                        <div class="d-flex justify-content-between align-items-center mt-4">
                                            <a href="{{ url('/moviedetail/'.$m->movie_id) }}" class="btn btn-warning" style="width: 48%;">Detail</a>
                                            <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark" style="width: 48%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                        </div>
                                        @else
                                        <div class="d-flex justify-content-between align-items-center mt-4">
                                            @if( Auth::user()->roles  == 1)
                                            <a href="{{ url('/moviedetail/'.$m->movie_id) }}" class="btn btn-warning" style="width: 48%;">Detail</a>
                                            <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark" style="width: 48%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                            @elseif ( Auth::user()->roles  == 2 )
                                            <a href="/moviemanagement/editForm/{{ $m->movie_id }}" class="btn btn-warning" style="width: 48%;">Edit</a>
                                            <a href="/moviemanagement/delete/{{ $m->movie_id }}" class="btn btn-danger" style="width: 48%;" onclick="return confirm('Are you sure you want to delete this movie?')">Delete</a>
                                            @else
                                            <a href="{{ url('/moviedetail/'.$m->movie_id) }}" class "btn btn-warning" style="width: 48%;">Detail</a>
                                            <a href="/addwatchlist/{{ $m->movie_id}}" class="btn btn-dark" style="width: 48%;"><i class="bi bi-plus-lg"></i> Watchlist</a>
                                            @endif
                                        </div>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                                @php
                                $count++;
                                @endphp
                            @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>


        <div class="row mt-5">
            <div class="col-12">
                <h2 class="h2_top10">Community</h2>
                @guest
                <form method="post" action="/review" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3 mt-3">
                            <input name="movie" type="hidden"  value="{{ $m->movie_id }}" class="form-control" aria-describedby="basic-addon1" readonly disabled>
                        <div class="input-group">
                            <span class="input-group-text">Guest:</span>
                            <textarea name="comment" class="form-control" placeholder="Add a comment..." aria-label="With textarea" required disabled></textarea>
                        </div>
                        <div class="input-group mt-3">
                            <input class="btn btn-outline-primary" type="submit" value="review">
                        </div>
                    </div>
                </form>
                @else
                <form method="post" action="/review" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3 mt-3">
                    <div class="input-group mb-3">
                                <input name="movie" value="{{ $m->movie_id }}" type="hidden" class="form-control hide"
                                    placeholder="{{ $m->movie_name }}" aria-label="Username" aria-describedby="basic-addon1"
                                    readonly>
                            </div>
                        <div class="input-group">
                            <span class="input-group-text">{{ Auth::user()->name }}:</span>
                            <textarea name="comment" class="form-control" placeholder="Add a comment..." aria-label="With textarea" required></textarea>
                        </div>
                        <div class="input-group mt-3">
                            <input class="btn btn-outline-primary" type="submit" value="Post">
                        </div>
                    </div>
                </form>

                @endguest
                <hr class="mt-5">
                @foreach ($reviews as $rv)
                @foreach ($user as $u)
                @guest
                    @if($rv->user_id == $u->id && $rv->movie_id == $m->movie_id)
                    <div class="card mb-3 mt-3">
                        <div class="card-body">
                          <p><h5>{{ $u->name }}</h5> <small>{{ \Carbon\Carbon::parse($rv->created_at)->format('F j Y') }}</small></p>
                            <h5>{{ $rv->review_info }}</h5>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseComment_{{ $rv->id }}" aria-expanded="false" aria-controls="collapseComment_{{ $rv->id }}">Reply view</button>
                            <div class="collapse" id="collapseComment_{{ $rv->id }}">
                                @foreach ($replies as $rp)
                                @if($rp->review_id == $rv->id)
                                <div class="card mb-3 mt-3">
                                    <div class="card-body">
                                        <p><h5>@foreach ($user as $u) @if($u->id == $rp->user_id)
                                            {{ $u->name }}
                                        @endif @endforeach</h5><small>{{ \Carbon\Carbon::parse($rv->created_at)->format('F j Y') }}</small></p>
                                        <h5>{{ $rp->reply_info }}</h5><br>
                                      </div>
                                </div>
                                @endif
                                @endforeach
                              </div>
                        </div>
                      </div>
                    @endif
                @else
                    @if($rv->user_id == $u->id && $rv->movie_id == $m->movie_id && Auth::user()->roles == 1)
                    <div class="card mb-3 mt-3">
                        <div class="card-body">
                          <p><h5>{{ $u->name }}</h5> <small>{{ \Carbon\Carbon::parse($rv->created_at)->format('F j Y') }}</small></p>
                            <h5>{{ $rv->review_info }}</h5>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseComment_{{ $rv->id }}" aria-expanded="false" aria-controls="collapseComment_{{ $rv->id }}">Reply view</button>
                                @if(Auth::user()->id == $rv->user_id)
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal_{{ $rv->id }}"><i class="bi bi-pencil-square"></i></button>
                                <a href="/delcomment/{{ $rv->id }}"><button type="button" class="btn btn-outline-danger" onclick="return confirm('คุณจะลบจริงหรือไม่?')"><i class="bi bi-trash-fill"></i></button></a>
                                @endif
                            <div class="collapse" id="collapseComment_{{ $rv->id }}">
                                        <form class="mt-3" method="POST" action="{{ route('addReply') }}">
                                            @csrf
                                            <div class="input-group">
                                                <span class="input-group-text">{{ Auth::user()->name }}:</span>
                                                <textarea name="reply" class="form-control" aria-label="With textarea" placeholder="Add a reply..." required></textarea>
                                                </span>
                                              </div>
                                              <input name="id" type="hidden" value="{{ Auth::user()->id }}">
                                              <input name="review" type="hidden" value="{{ $rv->id }}">
                                              <div class="input-group mt-3">
                                                <input class="btn btn-outline-primary" type="submit" value="Reply">
                                            </div>
                                        </form>
                                @foreach ($replies as $rp)
                                @if($rp->review_id == $rv->id)
                                <div class="card mb-3 mt-3">
                                    <div class="card-body">
                                        <p><h5>@foreach ($user as $u) @if($u->id == $rp->user_id)
                                            {{ $u->name }}
                                        @endif @endforeach</h5><small>{{ \Carbon\Carbon::parse($rv->created_at)->format('F j Y') }}</small></p>
                                        <h5>{{ $rp->reply_info }}</h5>
                                        @if(Auth::user()->id == $rp->user_id)
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal_{{ $rp->id }}"><i class="bi bi-pencil-square"></i></button>
                                        @endif
                                        @if(Auth::user()->id == $rp->user_id || Auth::user()->roles == 2)
                                        <a href="/delreply/{{ $rp->id }}"><button type="button" class="btn btn-outline-danger"><i class="bi bi-trash-fill"></i></button></a>
                                        @endif
                                      </div>
                                </div>
                                @endif
                                @endforeach
                              </div>
                        </div>
                      </div>
                    @elseif(Auth::user()->roles == 2 && $rv->user_id == $u->id && $rv->movie_id == $m->movie_id)
                    <div class="card mb-3 mt-3">
                        <div class="card-body">
                          <p><h5>{{ $u->name }}</h5> <small>{{ \Carbon\Carbon::parse($rv->created_at)->format('F j Y') }}</small></p>
                            <h5>{{ $rv->review_info }}</h5>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseComment_{{ $rv->id }}" aria-expanded="false" aria-controls="collapseComment_{{ $rv->id }}">Reply view</button>
                                @if(Auth::user()->id == $rv->user_id)
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal_{{ $rv->id }}"><i class="bi bi-pencil-square"></i></button>
                                @endif
                                <a href="/delcomment/{{ $rv->id }}"><button type="button" class="btn btn-outline-danger" onclick="return confirm('คุณจะลบจริงหรือไม่?')"><i class="bi bi-trash-fill"></i></button></a>
                            <div class="collapse" id="collapseComment_{{ $rv->id }}">
                                        <form class="mt-3" method="POST" action="{{ route('addReply') }}">
                                            @csrf
                                            <div class="input-group">
                                                <span class="input-group-text">{{ Auth::user()->name }}:</span>
                                                <textarea name="reply" class="form-control" aria-label="With textarea" placeholder="Add a reply..." required></textarea>
                                                </span>
                                              </div>
                                              <input name="id" type="hidden" value="{{ Auth::user()->id }}">
                                              <input name="review" type="hidden" value="{{ $rv->id }}">
                                              <div class="input-group mt-3">
                                                <input class="btn btn-outline-primary" type="submit" value="Reply">
                                            </div>
                                        </form>
                                @foreach ($replies as $rp)
                                @if($rp->review_id == $rv->id)
                                <div class="card mb-3 mt-3">
                                    <div class="card-body">
                                        <p><h5>@foreach ($user as $u) @if($u->id == $rp->user_id)
                                            {{ $u->name }}
                                        @endif @endforeach</h5><small>{{ \Carbon\Carbon::parse($rv->created_at)->format('F j Y') }}</small></p>
                                        <h5>{{ $rp->reply_info }}</h5>
                                        @if(Auth::user()->id == $rp->user_id)
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal_{{ $rp->id }}"><i class="bi bi-pencil-square"></i></button>
                                        @endif
                                        <a href="/delreply/{{ $rp->id }}"><button type="button" class="btn btn-outline-danger"><i class="bi bi-trash-fill"></i></button></a>
                                      </div>
                                </div>
                                @endif
                                @endforeach
                              </div>
                        </div>
                      </div>
                    @endif
                @endguest
                @endforeach
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection

