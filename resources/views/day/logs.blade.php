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
                    <table class="table table-sm table-striped dataTable">
                        <thead>
                            <th>ID</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->logCategory->name }}</td>
                                    <td>{{ $log->action }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>ID</th>
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
@section("scripts")
<script>
    $('.dataTable').DataTable({
        "pageLength": 25,
        "order" : [[0, "desc"]],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ]
    });
</script>
@endsection