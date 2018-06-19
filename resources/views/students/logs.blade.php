<?php
/**
 * Created by PhpStorm.
 * User: kevan
 * Date: 6/19/18
 * Time: 1:11 PM
 */ ?>

@extends("layouts.app")
@section("content")
    <div class="container">
        <h1 class="my-3">Logs</h1>
        <div class="row">
            <div class="col-12">
                <div class="row sticker">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sur la selection ...
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item action" id="editChecks" href="#"><i class="pr-2 fas fa-user-clock"></i> Editer Check-in / out</a>
                            <a class="dropdown-item action" id="editPangs" href="#"><i class="pr-2 far fa-chart-bar"></i> Ajouter / retirer pangs</a>
                            <a class="dropdown-item action" id="justify" href="#"><i class="pr-2 fas fa-file-medical"></i> Ajouter une excuse</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="dataTable">
                        <thead>
                            <th>User</th>
                            <th>Category</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->logCategory->name }}</td>
                                    <td>{{ $log->action }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>User</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection