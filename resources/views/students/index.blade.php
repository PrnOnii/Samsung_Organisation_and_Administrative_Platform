<?php
/**
 * Created by PhpStorm.
 * User: kevan
 * Date: 5/18/18
 * Time: 7:05 PM
 */ ?>

@extends("layouts.app")
@section("content")
    <div class="container">
        <h1 class="mt-3 mb-3">Liste des etudiants</h1>
        <div class="row">
            <div class="col-12">
                <table class="table dataTable">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Pangs</th>
                        <th>Promotion</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->pangs }}</td>
                            <td>{{ $student->promo->name }}</td>
                            <td>
                            @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null)
                                {{ $student->checkIn->arrived_at }}
                            @else
                                <form method="post" class="checkIn" action="{{ route("checkIn") }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" value="{{ $student->id }}">
                                    <button type="submit" class="btn btn-success">Check-In</button>
                                </form>
                            @endif
                            </td>
                            <td>
                            @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->leaved_at !== null)
                                {{ $student->checkIn->leaved_at }}
                            @else
                                @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null)
                                <form method="post" class="checkOut" action="{{ route("checkOut") }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" value="{{ $student->id }}">
                                    <button type="submit" class="btn btn-warning">Check-Out</button>
                                </form>
                                @endif
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Pangs</th>
                            <th>Promotion</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection