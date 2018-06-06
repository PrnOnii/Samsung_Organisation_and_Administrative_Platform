<?php
/**
 * Created by PhpStorm.
 * User: kevan
 * Date: 5/28/18
 * Time: 4:08 PM
 */ ?>

@extends("layouts.app")
@section("content")
    <div class="container">
        @foreach($student->day as $day)
            <p>Arrived at {{ $day->arrived_at }} on the {{ $day->day }}, and leaved at {{ $day->leaved_at }}</p>
        @endforeach
    </div>
@endsection