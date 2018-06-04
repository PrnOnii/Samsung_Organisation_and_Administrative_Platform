<?php

namespace App\Http\Controllers;

use App\EditPang;
use App\Jobs\ProcessPangs;
use App\Student;
use App\Promo;
use App\Pang;
use App\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
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
        //
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

    /**
     * Show the form for editing check-in andd check-out
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editChecks() {
        $students = Student::all();
        return view("day.editChecks", compact("students"));
    }

    /**
     * Update the specified resource in storage
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateChecks(Request $request) {
        $request->validate([
            "students" => "required",
            "day" => "required|date",
        ]);

        foreach ($request->input("students") as $student_id) {
            if ($request->input("arrived_at") !== null) {
                Day::where("day", $request->input("day"))
                    ->where("student_id", $student_id)
                    ->update([
                        "arrived_at" => $request->input("arrived_at")
                    ]);
            }
            if ($request->input("leaved_at") !== null) {
                Day::where("day", $request->input("day"))
                    ->where("student_id", $student_id)
                    ->update([
                        "leaved_at" => $request->input("leaved_at")
                    ]);
            }
            ProcessPangs::dispatch(Student::find($student_id), $request->input("day"));
        }

        return redirect("/");
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function justify() {
        $students = Student::all();
        return view("day.excuse", compact("students"));
    }

    /**
     * Store the newly created resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeJustify(Request $request) {
        $request->validate([
            "students" => "required",
            "day" => "required|date",
            "reason" => "required",
        ]);

        foreach($request->input("students") as $student_id){
            Day::where("day", $request->input("day"))
                ->where("student_id", $student_id)
                ->update([
                    "excused" => true,
                    "reason" => $request->input("reason")
                ]);
            ProcessPangs::dispatch(Student::find($student_id), $request->input("day"));
        }

        return redirect("/");
    }

    public function editPangs () {
        $students = Student::all();
        return view("day.editPangs", compact("students"));
    }

    public function updatePangs (Request $request) {
        $request->validate([
            "students" => "required",
            "day" => "required",
            "quantity" => "required",
            "reason" => "required",
        ]);

        foreach ($request->input("students") as $student_id) {
            EditPang::create([
                "student_id" => $student_id,
                "day" => $request->input("day"),
                "quantity" => $request->input("quantity"),
                "reason" => $request->input("reason"),
            ]);
            ProcessPangs::dispatch(Student::find($student_id), $request->input("day"));
        }

        return redirect("/");
    }

}
