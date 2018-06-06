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
    <div class="container">
        <canvas class="col-md-8 offset-md-2" id="attendanceChart"></canvas>
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
    var myChart = new Chart (ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Pangs',
                backgroundColor: 'red',
                borderColor: 'blue',
                data: pangs,
                fill: false
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 1000
                    }
                }]
            }
        }
    });

    // Chart Attendance
    var ctx2 = $("#attendanceChart");
    var days2 = [];
    var checkIn = [];
    var checkOut = [];
    @foreach ($student->attendanceHistory[0] as $day => $arrived_at)
        @if ($arrived_at !== null)
            days2.push("{{ $day }}");
            checkIn.push(moment("1970-02-01 {{ $arrived_at }}").valueOf());
        @endif
    @endforeach
    @foreach ($student->attendanceHistory[1] as $day => $leaved_at)
        @if ($leaved_at !== null)
            checkOut.push(moment("1970-02-01 {{ $leaved_at }}").valueOf());
        @elseif ($student->attendanceHistory[0][$day] !== null)
            checkOut.push(moment("1970-02-01 13:30:00").valueOf());
        @endif
    @endforeach
        console.log(checkIn);
        console.log(checkOut);
    var myChart2 = new Chart (ctx2, {
        type: "line",
        data: {
            labels: days2,
            datasets: [{
                label: "CheckIns",
                backgroundColor: "white",
                borderColor: "yellow",
                data: checkIn,
                fill: true
            }, {
                label: "checkOut",
                backgroundColor: "yellow",
                borderColor: "yellow",
                data: checkOut,
                fill: true
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    type: "linear",
                    position: "left",
                    ticks: {
                        min: moment('1970-02-01 08:00:00').valueOf(),
                        max: moment('1970-02-01 20:00:00').valueOf(),
                        stepSize: 3.6e+6,
                        beginAtZero: false,
                        callback: value => {
                            let date = moment(value);
                            if(date.diff(moment('1970-02-01 23:59:59'), 'minutes') === 0) {
                                return null;
                            }

                            return date.format('h:mm:ss a');
                        }
                    }
                }]
            }
        }
    });
</script>
@endsection