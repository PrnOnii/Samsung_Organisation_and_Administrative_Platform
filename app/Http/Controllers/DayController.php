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
        $this->middleware('admin');
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
            "action" => $date->toDateTimeString() . " : $user->name a pointé " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name),
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
            "action" => $date->toDateTimeString() . " : $user->name a dépointé " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name),
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
                    "action" => $date->toDateTimeString() . " : $user->name a modifié l'heure de pointage de " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " de $day->arrived_at à " . $request->input("arrived_at") . ":00 le " . $request->input("day"),
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
                    "action" => $date->toDateTimeString() . " : $user->name a modifié l'heure de dépointage de " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " de $day->leaved_at à " . $request->input("leaved_at") . ":00 le " . $request->input("day"),
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

            if ( $day = Day::where("day", $request->input("day"))->where("student_id", $student->id)->first() ) {
                $day->update([
                    "excused" => true,
                    "reason" => $request->input("reason")
                ]);
            } else {
                Day::create([
                    "student_id" => $student->id,
                    "day" => $request->input("day"),
                    "difference" => 0,
                    "excused" => 1,
                    "reason" => $request->input("reason")
                ]);
            }

            Log::create([
                "user_id" => Auth::id(),
                "category_id" => 3,
                "action" => $date->toDateTimeString() . " : $user->name a ajouté une excuse à " . ucfirst($student->first_name) . " " . ucfirst($student->last_name) . " le " . $request->input("day") . " : " . $request->input("reason"),
            ]);

            ProcessPangs::dispatch(Student::find($student_id), $request->input("day"));
        }

        return redirect("/");
    }

    public function deleteJustify($id) {
        $justify = Day::find($id);
        $justify->update([
            "excused" => false,
            "reason" => null,
        ]);
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

            $sign = ($request->input("quantity") > 0) ? "ajouté" : "retiré";
            Log::create([
                "user_id" => Auth::id(),
                "category_id" => 5,
                "action" => $date->toDateTimeString() . " : $user->name a $sign " . abs($request->input('quantity')) . " pangs à " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " : " . $request->input("reason"),
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

    public function deleteEditPangs (Int $id) {
        $edit = EditPang::find($id);
        $edit->delete();
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
        return view("day.logs", compact("logs"));
    }
}
