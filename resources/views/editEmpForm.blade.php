@extends('layouts.navbar')
@section('content')
<div class="container-fluid">
    @foreach ($edit_emp as $ed)
    <form method="post" action="/moviemanagementEmp/updateEmp" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">
            <div class="col-12">
                <header>Employee Detail</header>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" id="basic-addon1">EmpID:</span>
                    <input type="text" name="id" placeholder="EMPXXX" class="form-control" value="{{ $ed->emp_id }}" aria-describedby="basic-addon1" required readonly>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Name:</span>
                    <input type="text" name="name" class="form-control" aria-describedby="basic-addon1" value="{{ $ed->emp_name }}" required>
                </div>
                <a href=""><button type="submit" class="btn btn-success">Save</button></a>
        @endforeach
                <div class="container-fluid mt-3">
                    <div class="row">
                        <header>Select Performance</header>
                    </div>
                    <div class="row mt-2">
                        @foreach ($movie as $m)
                            <div class="col-3">
                                <div class="card mt-4" style="width: auto">
                                    <a href="/moviedetail/{{ $m->movie_id }}">
                                        <img class="card-img-managment-edit" src="{{ asset('Materials/Movies/' . $m->movie_id . '.png') }}" alt="Movie poster" width="300px" height="450px"/>
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $m->movie_name }}</h5>
                                        <p class="card-text">
                                            <i class="bi bi-star-fill text-warning"> </i> {{ $m->movie_score }}
                                        </p>
                                        <p>
                                            <a href="">{{ $m->movie_year_on_air }}</a> -
                                            @foreach ($ctr as $r)
                                                @if ($m->critical_rate == $r->ctr_id)
                                                <a href="">{{ $r->ctr_name }}</a>
                                                @endif
                                            @endforeach
                                            - {{ number_format($m->movie_time / 60, 1) }}h
                                            </p>
                                                </div>
                                                        <div class="card-body">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-text">
                                                                    <!-- เพิ่ม hidden input field สำหรับ movie_id -->
                                                                    <input type="hidden" name="movie_ids[]" value="{{ $m->movie_id }}">
                                                                    <input name="movie_{{ $m->movie_id }}" class="form-check-input mt-0" type="checkbox" id="myCheckbox_{{ $m->movie_id }}" value="{{ $m->movie_id }}" aria-label="Checkbox for following text input" @foreach($edit_detail as $dt) @if($dt->movie_id == $m->movie_id) checked @endif @endforeach>
                                                                    </div>
                                                                    <select name="type_{{ $m->movie_id }}" class="form-select" id="mySelect_{{ $m->movie_id }}" aria-label="Default select example" @if(!$edit_detail->contains('movie_id', $m->movie_id)) disabled @endif>
                                                                        <option value="">Select Role</option>
                                                                        @foreach ($empt as $et)
                                                                            <option @foreach ($edit_detail as $dt)
                                                                                @if($dt->emp_type_id == $et->emp_type_id && $dt->movie_id == $m->movie_id)
                                                                                    selected
                                                                                @endif
                                                                            @endforeach value="{{ $et->emp_type_id }}">{{ $et->emp_type_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    const checkbox_{{ $m->movie_id }} = document.getElementById('myCheckbox_{{ $m->movie_id }}');
                                                    const select_{{ $m->movie_id }} = document.getElementById('mySelect_{{ $m->movie_id }}');
                                                    checkbox_{{ $m->movie_id }}.addEventListener('change', function () {
                                                        if (checkbox_{{ $m->movie_id }}.checked) {
                                                            select_{{ $m->movie_id }}.removeAttribute('disabled');
                                                        } else {
                                                            select_{{ $m->movie_id }}.setAttribute('disabled', 'true');
                                                        }
                                                    });
                                                </script>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
</div>
 @endsection
