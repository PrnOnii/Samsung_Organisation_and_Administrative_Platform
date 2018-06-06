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
        <canvas class="col-md-8 offset-md-2" id="pangsChart"></canvas>
    </div>
@endsection
@section("scripts")
<script defer>
    // Charts Pangs
    var ctx = $("#pangsChart");
    var days = [];
    var pangs = [];
    @foreach($student->pangsHistory as $day => $pangs)
    days.push("{{ $day }}");
    pangs.push("{{ $pangs }}");
    @endforeach
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Pangs',
                backgroundColor: 'red',
                borderColor: 'blue',
                data: pangs,
                fill: false,
            }],
        },
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 1000 
                }
            }]
        }
    });
</script>
@endsection