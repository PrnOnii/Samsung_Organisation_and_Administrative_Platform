<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPangs;
use App\Student;
use App\Promo;
use App\PangSettings;
use App\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (!PangSettings::first())
            PangSettings::create([
                "current_promo_id" => 1,
            ]);
    }
    /**
     * Display a listing of the resource.
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
        return redirect("/student");
    }

    public function createBulk() {
        $promotions = Promo::all();
        return view("students.addBulk", compact("promotions"));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            // regex:/(([a-zA-Z]|-)+(\s){1}([a-zA-Z]|-)+)/m
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
        $days = Day::where("student_id", $student->id)->orderBy("day", "asc")->get();
        $pangHistory = [];
        $total = 1000;
        foreach($days as $day)
        {
            $total += $day->difference;
            if($total > 1000)
                $total = 1000;
            if($total < 0)
                $total = 0;
            $pangHistory[$day->day] = $total;
        }
        $student->pangHistory = $pangHistory;
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
     * @param Student $student
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(student $student, Request $request)
    {
        if ($student->delete()) {
            $request->session()->flash('confirmation-success', "L'élève a bien été supprimé de la base de donnée");
            return redirect("/");
        } else {
            $request->session()->flash('confirmation-danger', "Il y a eu une erreur lors de la suppression du profil selectionné");
            return redirect("/student/" . $student->id);
        }
    }

    public function checkIn(Request $request) {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $request->input("id"))
            ->update(["arrived_at" => $date->toTimeString() ]);
        ProcessPangs::dispatch();
        echo $date->toTimeString();
    }

    public function checkOut(Request $request) {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $request->input("id"))
            ->update(["leaved_at" => $date->toTimeString()]);
        ProcessPangs::dispatch();
        echo $date->toTimeString();
    }
}
