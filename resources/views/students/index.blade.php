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
                <div class="dropdown mb-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sur la selection ...
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item action" id="editChecks" href="#">Editer Check-in / out</a>
                        <a class="dropdown-item action" id="editPangs" href="#">Ajouter / retirer pangs</a>
                        <a class="dropdown-item action" id="justify" href="#">Ajouter une excuse</a>
                    </div>
                </div>
                <table class="table table-striped dataTable">
                    <thead>
                    <tr>
                        <th><i class="far fa-check-square"></i></th>
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
                            @if ($student->pangs <= 0)
                                <tr class="table-danger">
                            @elseif ($student->pangs <= 100)
                                <tr class="table-warning">
                            @else
                                <tr>
                            @endif
                            <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable"><input name="students[]" value="{{ $student->id }}" type="checkbox"></td>
                            <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">{{ $student->first_name }}</td>
                            <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">{{ $student->last_name }}</td>
                            <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">{{ $student->pangs }}</td>
                            <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">{{ $student->promo->name }}</td>
                            <td>
                            @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null)
                                {{ $student->checkIn->arrived_at }}
                            @else
                                <form method="post" class="checkIn" action="{{ route("checkIn") }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" value="{{ $student->id }}">
                                    <button type="submit" class="btn btn-success btn-sm">Check-In</button>
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
                                    <button type="submit" class="btn btn-warning btn-sm">Check-Out</button>
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