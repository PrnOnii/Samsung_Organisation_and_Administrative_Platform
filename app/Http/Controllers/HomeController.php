<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Day;
use App\Jobs\ProcessPangs;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware("guest");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ProcessPangs::dispatch();
        $students = Student::all();
        foreach ($students as $student) {
            $days = Day::where("student_id", $student->id)->orderBy("day", "asc")->get();
            $lastItem = count($days) - 1;
            $student->checkIn = $days[$lastItem];
            $total = 1000;
            foreach($days as $day)
            {
                $total += $day->difference;
                if($total > 1000)
                    $total = 1000;
                if($total < 0)
                    $total = 0;
            }
            $student->pangs = $total;
        }
        return view("home", compact("students"));
    }

    public function notAllowed()
    {
        return view('auth.notallowed');
    }
}
