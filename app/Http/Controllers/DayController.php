<?php

namespace App\Http\Controllers;

use App\EditPang;
use App\Jobs\ProcessPangs;
use App\PangSettings;
use App\Student;
use App\Promo;
use App\Pang;
use App\Day;
use App\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function checkIn(Request $request) {
        $date = Carbon::now("Europe/Paris");
        $student = Student::where("id", $request->input("id"))->first();
        $user = Auth::user();

        Day::where("day", $date->toDateString())
            ->where("student_id", $student->id)
            ->update(["arrived_at" => $date->toTimeString() ]);

        Log::create([
            "user_id" => Auth::id(),
            "category_id" => 1,
            "action" => $date->toDateTimeString() . " : " . $user->name . " has checked " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " in.",
        ]);
        ProcessPangs::dispatch();
        echo $date->toTimeString();
    }

    public function checkOut(Request $request) {
        $date = Carbon::now("Europe/Paris");
        $student = Student::where("id", $request->input("id"))->first();
        $user = Auth::user();

        Day::where("day", $date->toDateString())
            ->where("student_id", $student->id)
            ->update(["leaved_at" => $date->toTimeString()]);

        Log::create([
            "user_id" => Auth::id(),
            "category_id" => 1,
            "action" => $date->toDateTimeString() . " : " . $user->name . " has checked " . $student->first_name  . " " .ucfirst($student->last_name) . " out.",
        ]);

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
        ProcessPangs::dispatch(null, $request->input("day"));

        $date = Carbon::now("Europe/Paris");
        $user = Auth::user();

        foreach ($request->input("students") as $student_id) {
            $student = Student::where("id", $student_id)->first();
            $day = Day::where("day", $request->input("day"))
                ->where("student_id", $student_id)
                ->first();

            if ($request->input("arrived_at") !== null) {

                Log::create([
                    "user_id" => Auth::id(),
                    "category_id" => 2,
                    "action" => $date->toDateTimeString() . " : " . $user->name . " updated check-in time from  " . $day->arrived_at . " to " . $request->input("arrived_at") . ":00 for " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " on " . $request->input("day"),
                    ]);

                Day::where("day", $request->input("day"))
                    ->where("student_id", $student->id)
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

                Log::create([
                    "user_id" => Auth::id(),
                    "category_id" => 2,
                    "action" => $date->toDateTimeString() . " : " . $user->name . " updated check-in time from  " . $day->arrived_at . " to " . $request->input("arrived_at") . ":00 for " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " on " . $request->input("day"),
                ]);
            }
        }
        ProcessPangs::dispatch(null, $request->input("day"));

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
        $date = Carbon::now("Europe/Paris");
        $user = Auth::user();

        foreach($request->input("students") as $student_id){
            $student = Student::where("id", $student_id)->first();

            Day::where("day", $request->input("day"))
                ->where("student_id", $student->id)
                ->update([
                    "excused" => true,
                    "reason" => $request->input("reason")
                ]);

            Log::create([
                "user_id" => Auth::id(),
                "category_id" => 3,
                "action" => $date->toDateTimeString() . " : " . $user->name . " added an excuse on " . $request->input("day") . "for " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " : " . $request->input("reason"),
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
        $date = Carbon::now("Europe/Paris");
        $user = Auth::user();

        foreach ($request->input("students") as $student_id) {
            $student = Student::where("id", $student_id)->first();

            Log::create([
                "user_id" => Auth::id(),
                "category_id" => 3,
                "action" => $date->toDateTimeString() . " : " . $user->name . " added " . $request->input("quantity") . " pangs for " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " : " . $request->input("reason"),
            ]);

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

    public function editPangSettings () {
        $ettings = PangSettings::all();
        return view("day.pangSettings", compact("settings"));
    }

    public function updatePangSettings (Request $request) {
        $request->validate([
            "morning_early" => "required",
            "morning_start" => "required",
            "morning_late" => "required",
            "morning_end" => "required",
            "afternoon_start" => "required",
            "afternoon_leave" => "required",
            "afternoon_extra" => "required",
            "afternoon_end" => "required",
            "earning_pang" => "required",
            "losing_pang" => "required",
            "absent_loss" => "required",
            "current_promo_id" => "required|integer|gt:0"
        ]);
    }

    public function logs()
    {
        $logs = Log::all();
        return view("students.logs", compact("logs"));
    }
}
