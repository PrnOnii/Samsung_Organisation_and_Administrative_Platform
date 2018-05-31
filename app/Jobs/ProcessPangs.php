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

        $this->date = ($day !== null) ? $this->date = Carbon::createFromDate($day) : Carbon::now("Europe/Paris");

        $this->morning_early = Carbon::createFromTimeString($this->settings->morning_early);
        $this->morning_start = Carbon::createFromTimeString($this->settings->morning_start);
        $this->morning_late = Carbon::createFromTimeString($this->settings->morning_late);
        $this->morning_end = Carbon::createFromTimeString($this->settings->morning_end);
        $this->afternoon_start = Carbon::createFromTimeString($this->settings->afternoon_start);
        $this->afternoon_leave = Carbon::createFromTimeString($this->settings->afternoon_leave);
        $this->afternoon_extra = Carbon::createFromTimeString($this->settings->afternoon_extra);
        $this->afternoon_end = Carbon::createFromTimeString($this->settings->afternoon_end);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->students as $student) {
            if ( !$day = Day::where("day", $this->date->toDateString())->where("student_id", $student->id)->first() ) {
                $day = Day::create([
                    "student_id" => $student->id,
                    "day" => $this->date->toDateString(),
                    "difference" => 0,
                ]);
            }
            $arrive = ($day->arrived_at !== null) ? Carbon::createFromTimeString($day->arrived_at) : null;
            $leave = ($day->leaved_at !== null) ? Carbon::createFromTimeString($day->leaved_at) : null;


            $morning_loss = 0;
            $morning_gain = 0;
            $afternoon_loss = 0;
            $afternoon_gain = 0;
            $morning_absent = false;
            $afternoon_absent = false;

            //  && $this->date < $this->afternoon_end

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
                if ($leave === null && $this->date->toTimeString() > $this->afternoon_end) {
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

                $difference = $morning_gain - $morning_loss + $afternoon_gain - $afternoon_loss;

                // Update difference in days table
                Day::where("day", $this->date->toDateString())
                    ->where("student_id", $student->id)
                    ->update(["difference" => $difference]);
            }
            else {
                if ($this->date->toTimeString() > $this->afternoon_end) {
                    $morning_loss = $this->settings->absent_loss;
                    $afternoon_loss = $this->settings->absent_loss;
                    $morning_absent = true;
                    $afternoon_absent = true;

                    Day::where("day", $this->date->toDateString())
                        ->where("student_id", $student->id)
                        ->update(["difference" => (-2 * $this->settings->absent_loss) ]);
                }
            }
        }
    }
}