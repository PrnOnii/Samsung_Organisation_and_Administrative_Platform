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

    /**
     * Create a new job instance.
     *
     * @param \App\Student $student
     * @return void
     */
    public function __construct(Student $student = null)
    {
        $this->settings = PangSettings::first();
        if ($student !== null) {
            $this->students = $student;
        } else {
            $this->students = Student::where("promo_id", $this->settings->current_promo_id)->get();
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $this->students->id)
            ->update(["difference" => "32"]);

    }
}
