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
        <canvas id="pangsChart" width="400" height="400"></canvas>
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