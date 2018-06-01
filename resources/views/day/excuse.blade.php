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
        <h1 class="mt-3 mb-3">Entrer une excuse</h1>
        <div class="row">
            <div class="col-8 offset-2">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('/justify') }}">
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
                                <label for="reason">Raison</label>
                                <textarea id="reason" class="form-control" name="reason" required></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Valider l'excuse
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection