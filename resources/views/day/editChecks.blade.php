<?php
/**
 * Created by PhpStorm.
 * User: kevan
 * Date: 5/31/18
 * Time: 4:07 PM
 */ ?>

@extends("layouts.app")
@section("content")
    <div class="container">
        <h1 class="mt-3 mb-3">Editer des horaires</h1>
        <div class="row">
            <div class="col-8 offset-2">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('/editChecks') }}">
                            {!! csrf_field() !!}

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="students">Etudiant(s)</label>
                                <select id="students" class="form-control" name="students[]" required multiple autofocus>
                                    @foreach($students as $student)
                                        <option value="{{$student->id}}">{{ $student->first_name }} {{ $student->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="day">Jour</label>
                                <input id="day" type="date" class="form-control" name="day" required>
                            </div>

                            <div class="form-group">
                                <label for="arrived_at">Check-In</label>
                                <input id="arrived_at" type="time" class="form-control" name="arrived_at">
                            </div>

                            <div class="form-group">
                                <label for="leaved_at">Check-Out</label>
                                <input id="leaved_at" type="time" class="form-control" name="leaved_at">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Editer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection