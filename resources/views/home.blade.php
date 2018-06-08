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
                <div class="table-responsive">
                    <table class="table table-sm table-striped dataTable">
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
                                <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">
                                    <a href="/student/{{ $student->id }}"> {{ ucfirst($student->first_name) }}</a>
                                </td>
                                <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">
                                    <a href="/student/{{ $student->id }}"> {{ ucfirst($student->last_name) }}</a>
                                </td>
                                <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">
                                    <h4>
                                    @if ($student->pangs <= 0)
                                        <span class="badge badge-danger">
                                    @elseif ($student->pangs <= 300)
                                        <span class="badge badge-warning">
                                    @elseif ($student->pangs <= 700)
                                        <span class="badge badge-info">
                                    @else
                                        <span class="badge badge-success">
                                    @endif
                                            {{ $student->pangs }}
                                        </span>
                                    </h4>
                                </td>
                                <td data-toggle="tooltip" title="<img src='https://cdn.local.epitech.eu/userprofil/profilview/{{ $student->first_name }}.{{ $student->last_name }}.jpg' />" class="clickable">{{ $student->promo->name }}</td>
                                <td>
                                    @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null)
                                        {{ $student->checkIn->arrived_at }}
                                    @else
                                        Check-in en attente
                                    @endif
                                </td>
                                <td>
                                    @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->leaved_at !== null)
                                        {{ $student->checkIn->leaved_at }}
                                    @else
                                        @if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null)
                                            Check-out en attente
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
    </div>
@endsection