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
        <h1>Students</h1>
        <div class="row">
            <a role="button" class="btn btn-success ml-4 my-2" href="/student/add"><i class="fas fa-plus"></i> Add Student</a>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table dataTable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Promotion</th>
                        <th>Pangs</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->promo->name }}</td>
                            <td>{{ $student->pang->total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Promotion</th>
                        <th>Pangs</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection