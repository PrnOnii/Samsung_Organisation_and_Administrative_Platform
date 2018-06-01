<?php

namespace App\Jobs;

use App\Student;
use App\PangSettings;
use App\Day;
use App\Pang;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPangs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $settings;
    private $students;
    private $date;
    private $morning_early;
    private $morning_start;
    private $morning_late;
    private $morning_end;
    private $afternoon_start;
    private $afternoon_leave;
    private $afternoon_extra;
    private $afternoon_end;

    /**
     * Create a new job instance.
     *
     * @param \App\Student $student
     * @param $day
     * @return void
     */
    public function __construct(Student $student = null, $day = null)
    {
        $this->settings = PangSettings::first();
        if ($student !== null) {
            $this->students = [$student];
        } else {
            $this->students = Student::where("promo_id", $this->settings->current_promo_id)->get();
        }

        $this->date = ($day !== null) ? Carbon::createFromFormat("Y-m-d", $day) : Carbon::now("Europe/Paris")->addHour(2);

        $this->morning_early = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_early);
        $this->morning_start = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_start);
        $this->morning_late = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_late);
        $this->morning_end = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_end);
        $this->afternoon_start = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_start);
        $this->afternoon_leave = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_leave);
        $this->afternoon_extra = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_extra);
        $this->afternoon_end = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_end);

        if ($day !== null) {
            $this->date->hour = $this->afternoon_end->hour;
            $this->date->minute = $this->afternoon_end->minute;
            $this->date->second = $this->afternoon_end->second;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!$this->date->isWeekend()) {
            foreach ($this->students as $student) {
                if ( !$day = Day::where("day", $this->date->toDateString())->where("student_id", $student->id)->first() ) {
                    $day = Day::create([
                        "student_id" => $student->id,
                        "day" => $this->date->toDateString(),
                        "difference" => 0,
                    ]);
                }
                $arrive = ($day->arrived_at !== null) ? Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $day->arrived_at) : null;
                $leave = ($day->leaved_at !== null) ? Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $day->leaved_at) : null;

                $morning_loss = 0;
                $morning_gain = 0;
                $afternoon_loss = 0;
                $afternoon_gain = 0;
                $morning_absent = false;
                $afternoon_absent = false;


                if ($arrive !== null) {

                    // Check pangs loss
                    // Morning
                    if($arrive >= $this->morning_end) {
                        $morning_loss = $this->settings->absent_loss;
                    } elseif($arrive > $this->morning_late) {
                        $morning_loss += $arrive->diffInMinutes($this->morning_late) * $this->settings->losing_pang;
                    }
                    if($leave !== null && $leave < $this->morning_end) {
                        $morning_loss += $leave->diffInMinutes($this->morning_end) * $this->settings->losing_pang;
                    }
                    $morning_loss = ($morning_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $morning_loss;
                    $morning_absent = ($morning_loss >= $this->settings->absent_loss) ? true : false;

                    // Afternoon
                    if ($leave === null && $this->date >= $this->afternoon_end) {
                        $afternoon_loss += $this->settings->absent_loss;
                    } else {
                        if( $leave != null) {
                            if ( $leave <= $this->afternoon_start) {
                                $afternoon_loss += $this->settings->absent_loss;
                            } elseif ($leave < $this->afternoon_leave) {
                                $afternoon_loss += $leave->diffInMinutes($this->afternoon_leave) * $this->settings->losing_pang;
                            }
                        }
                    }
                    if ($arrive > $this->afternoon_start) {
                        $afternoon_loss += $arrive->diffInMinutes($this->afternoon_start) * $this->settings->losing_pang;
                    }
                    $afternoon_loss = ($afternoon_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $afternoon_loss;
                    $afternoon_absent = ($afternoon_loss >= $this->settings->absent_loss) ? true : false;

                    // Check pangs gain
                    // Morning
                    if($arrive < $this->morning_start) {
                        if ($day->excused || !$morning_absent) {
                            $arrive = ($arrive < $this->morning_early) ? $this->settings->morning_early : $arrive;
                            $morning_gain = $arrive->diffInMinutes($this->morning_start) * $this->settings->earning_pang;
                        }
                    }

                    // Afternoon
                    if ($leave > $this->afternoon_extra) {
                        if ($day->excused || !$afternoon_absent) {
                            $leave = ($leave > $this->afternoon_end) ? $this->afternoon_end : $leave;
                            $afternoon_gain = $leave->diffInMinutes($this->afternoon_end) * $this->settings->earning_pang;
                        }
                    }

                    if($day->excused) {
                        $morning_loss = 0;
                        $afternoon_loss = 0;
                    }

                    $difference = $morning_gain - $morning_loss + $afternoon_gain - $afternoon_loss;

                    // Update difference in days table
                    Day::where("day", $this->date->toDateString())
                        ->where("student_id", $student->id)
                        ->update(["difference" => $difference]);
                }
                else {
                    // Cheking Absent loss
                    // Morning
                    if($this->date > $this->morning_late) {
                        $morning_loss += $this->date->diffInMinutes($this->morning_late) * $this->settings->losing_pang;
                        $morning_loss = ($morning_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $morning_loss;
                    }

                    // Afternoon
                    if($this->date > $this->afternoon_start) {
                        $afternoon_loss += $this->date->diffInMinutes($this->afternoon_start) * $this->settings->losing_pang;
                        $afternoon_loss = ($afternoon_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $afternoon_loss;
                    }

                    if ($this->date >= $this->afternoon_end) {
                        $morning_loss = $this->settings->absent_loss;
                        $afternoon_loss = $this->settings->absent_loss;
                        $morning_absent = true;
                        $afternoon_absent = true;
                    }

                    if($day->excused) {
                        $morning_loss = 0;
                        $afternoon_loss = 0;
                    }

                    $difference = $morning_gain - $morning_loss + $afternoon_gain - $afternoon_loss;

                    Day::where("day", $this->date->toDateString())
                        ->where("student_id", $student->id)
                        ->update(["difference" => $difference ]);
                }
            }
        }
    }
}
