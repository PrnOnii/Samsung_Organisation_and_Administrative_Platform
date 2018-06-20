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
        <h1 class="my-3">Profil de {{ $student->first_name }} {{ $student->last_name }}</h1>
        <h2 class="mb-3">{{ $student->total }} pangs</h2>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="checks-tab" data-toggle="tab" href="#checks" role="tab" aria-controls="checks" aria-selected="true">Check-in / out</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pangs-tab" data-toggle="tab" href="#pangs" role="tab" aria-controls="pangs" aria-selected="false">Pangs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="excuses-tab" data-toggle="tab" href="#excuses" role="tab" aria-controls="excuses" aria-selected="false">Excuses</a>
            </li>
        </ul>
        <div class="tab-content mb-5" id="myTabContent">
            <div class="tab-pane fade show active pt-3" id="checks" role="tabpanel" aria-labelledby="checks-tab">
                <table class="table table-sm table-striped dataTable">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($days as $day)
                        <tr>
                            <td>{{ $day->day }}</td>
                            <td>{{ $day->arrived_at }}</td>
                            <td>{{ $day->leaved_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Jour</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane fade pt-3" id="pangs" role="tabpanel" aria-labelledby="pangs-tab">
                <table class="table table-sm table-striped dataTable">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Quantité</th>
                            <th>Raison</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($student->pangs as $pang)
                        <tr>
                            <td>{{ $pang[0] }}</td>
                            <td>{{ $pang[1] }}</td>
                            <td>{{ $pang[3] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Jour</th>
                            <th>Quantité</th>
                            <th>Raison</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane fade pt-3" id="excuses" role="tabpanel" aria-labelledby="excuses-tab">
                <table class="table table-sm table-striped dataTable">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Raison</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($days as $day)
                        @if($day->excused)
                        <tr>
                            <td>{{ $day->day }}</td>
                            <td>{{ $day->reason }}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Jour</th>
                            <th>Raison</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <canvas class="col-md-8 offset-md-2" id="pangsChart"></canvas>
        <canvas class="col-md-8 offset-md-2" id="attendanceChart"></canvas>
    </div>
    @endsection
    @section("scripts")
    <script defer>
        $('.dataTable').DataTable({
            "pageLength": 10,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            }
        });
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
    var myChart2 = new Chart (ctx2, {
        type: "line",
        data: {
            labels: days2,
            datasets: [{
                label: "CheckIns",
                backgroundColor: "white",
                borderColor: "green",
                data: checkIn,
                fill: true
            }, {
                label: "checkOut",
                backgroundColor: "green",
                borderColor: "green",
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