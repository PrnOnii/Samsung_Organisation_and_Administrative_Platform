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
                <div style="display: none" id="alert-refresh" class="alert alert-info" role="alert">
                    Le rafraichissement automatique est desactive lorsque des etudiants sont selectionnes.
                </div>
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
                    <table id="ajaxStudents" class="table table-sm table-striped dataTable">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Pangs</th>
                            <th>Promotion</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><i class="far fa-check-square"></i></th>
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
@section('scripts')
<script>
    $(".sticker").sticky({topSpacing:20, zIndex: 99});
    $("#checkAll").click(function (){
        $('input:checkbox').prop('checked', this.checked);
    });
    var table = $('#ajaxStudents').DataTable({
        ajax: "/json",
        "pageLength": 50,
        "columns": [
            { className: "clickable" },
            { className: "clickable" },
            { className: "clickable" },
            { className: "clickable" },
            { className: "clickable" },
            { searchable: false },
            { searchable: false }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        }
    });
    var refresh;
    var checked = 0;
    function ajaxRefresh()
    {
        checked = $(":checkbox:checked").length;
        if(checked === 0)
        {
            $("#alert-refresh").hide();
            table.ajax.reload();
        }
        else
        {
            $("#alert-refresh").show();
        }
    }
    setInterval(ajaxRefresh, 5000);

</script>
@endsection