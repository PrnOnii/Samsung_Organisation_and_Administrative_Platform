<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPangs;
use App\Student;
use App\Promo;
use App\Pang;
use App\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();
        ProcessPangs::dispatch();
        foreach ($students as $student) {
            $student->checkIn = Day::orderBy("day", "desc")->where("student_id", $student->id)->first();
            $total = 1000;
            foreach($student->day as $day)
            {
                $total += $day->difference;
                if($total > 1000)
                    $total = 1000;
                if($total < 0)
                    $total = 0;
            }
            $student->pangs = $total;
        }
        return view("students.index", compact("students"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promotions = Promo::all();
        return view("students.add", compact("promotions"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "firstname" => "required",
            "lastname" => "required",
            "promotion" => "required",
        ]);

        $promotion = Promo::find($request->input("promotion"));
        $student = $promotion->student()->create([
            "first_name" => $request->input("firstname"),
            "last_name" => $request->input("lastname"),
        ]);
        $student->pang()->create();
        return redirect("/student");
    }

    public function createBulk() {
        $promotions = Promo::all();
        return view("students.addBulk", compact("promotions"));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            "names" => "required",
            "promotion" => "required",
        ]);

        $names = explode("\n", $request->input("names"));
        $promotion = Promo::find($request->input("promotion"));

        foreach ($names as $name) {
            $parts = explode(" ", $name);
            $student = $promotion->student()->create([
                "last_name" => $parts[0],
                "first_name" => $parts[1],
            ]);
            $student->pang()->create();
        }
        return redirect("/student");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $student = Student::find($id);
        return view("students.show", compact("student"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(student $student)
    {
        //
    }

    public function checkIn(Request $request) {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $request->input("id"))
            ->update(["arrived_at" => $date->toTimeString() ]);
        echo $date->toTimeString();
    }

    public function checkOut(Request $request) {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $request->input("id"))
            ->update(["leaved_at" => $date->toTimeString()]);
        ProcessPangs::dispatch(Student::find($request->input("id")));
        echo $date->toTimeString();
    }
}
