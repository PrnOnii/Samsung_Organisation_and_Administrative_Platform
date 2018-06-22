<?php

namespace App\Http\Controllers;

use App\Day;
use App\EditPang;
use App\Jobs\ProcessPangs;
use App\PangSettings;
use App\Promo;
use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except('show');
        if (!PangSettings::first()) {
            PangSettings::create([
                "current_promo_id" => 1,
            ]);
        }

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
            foreach ($days as $day) {
                $total += $day->difference;
                if ($total > 1000) {
                    $total = 1000;
                }

                if ($total < 0) {
                    $total = 0;
                }

            }
            $student->pangs = $total;
        }
        return view("students.index", compact("students"));
    }

    public function jsonStudentsData()
    {
        $data = [];
        ProcessPangs::dispatch();
        $students = Student::all();
        foreach ($students as $student) {
            $days = Day::where("student_id", $student->id)->orderBy("day", "asc")->get();
            $lastItem = count($days) - 1;
            $student->checkIn = $days[$lastItem];
            $total = 1000;
            foreach ($days as $day) {
                $total += $day->difference;
                if ($total > 1000) {
                    $total = 1000;
                }

                if ($total < 0) {
                    $total = 0;
                }

            }

            if ($total <= 0) {
                $student->pangs = '<h4><span class="badge badge-danger">' . $total . '</span></h4>';
            } else if ($total <= 300) {
                $student->pangs = '<h4><span class="badge badge-warning">' . $total . '</span></h4>';
            } else if ($total <= 700) {
                $student->pangs = '<h4><span class="badge badge-info">' . $total . '</span></h4>';
            } else {
                $student->pangs = '<h4><span class="badge badge-success">' . $total . '</span></h4>';
            }

            $tooltip = 'class="image-tooltip" data-tooltip-content="#image-'.$student->id.'"';
            $student->first_name_data = '<a '. $tooltip .' href="/student/' . $student->first_name . '.' . $student->last_name . '">' . ucfirst($student->first_name) . '</a>';
            $student->last_name_data = '<a '. $tooltip .' href="/student/' . $student->first_name . '.' . $student->last_name . '">' . ucfirst($student->last_name) . '</a>';

            if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null) {
                $checkIn = $student->checkIn->arrived_at;
            } else {
                $checkIn = '<form method="post" class="checkIn" action="' . route("checkIn") . '">
                ' . csrf_field() . '
                <input type="hidden" name="id" value="' . $student->id . '">
                <button type="submit" class="btn btn-success btn-sm">Check-In</button>
                </form>';
            }

            if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->leaved_at !== null) {
                $checkOut = $student->checkIn->leaved_at;
            } else {
                if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null) {
                    $checkOut = '<form method="post" class="checkOut" action="' . route("checkOut") . '">
                    ' . csrf_field() . '
                    <input type="hidden" name="id" value="' . $student->id . '">
                    <button type="submit" class="btn btn-warning btn-sm">Check-Out</button>
                    </form>';
                } else {
                    $checkOut = '';
                }
            }
            array_push($data, ['id' => $student->id, 'first_name' => $student->first_name_data, 'last_name' => $student->last_name_data, 'pangs' => $student->pangs, 'promo' => $student->promo->name, 'checkin' => $checkIn, 'checkout' => $checkOut]);
        }
        $json = ['data' => $data];
        echo json_encode($json);
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

    public function createBulk()
    {
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
    public function show(string $login)
    {
        $name = explode(".", $login);
        $firstname = $name[0] ?? "default";
        $lastname = $name[1] ?? "default";
        $student = Student::where("first_name", $firstname)->where("last_name", $lastname)->first();
        if(!$student)
            return redirect('/');
        if (Auth::user()->admin === 1 || Auth::user()->email === $student->email) {
            $days = Day::where("student_id", $student->id)->orderBy("day", "asc")->get();
            $pangsHistory = [];
            $attendanceHistory = [[], []];
            $pangs = [];
            $settings = PangSettings::first();
            $total = 1000;
            foreach ($days as $day) {
                $total += $day->difference;
                if ($total > 1000) {
                    $total = 1000;
                }

                if ($total < 0) {
                    $total = 0;
                }

                $attendanceHistory[0][$day->day] = $day->arrived_at;
                $attendanceHistory[1][$day->day] = $day->leaved_at;
                $pangsHistory[$day->day] = $total;
                $student->total = $total;
                $entry = [];
                if ($day->difference != 0) {
                    $entry[0] = $day->day;
                    if ($editPang = EditPang::where("day", $day->day)->where("student_id", $student->id)->get()) {
                        foreach ($editPang as $edit) {
                            $entry[0] = $day->day;
                            $entry[1] = $edit->quantity;
                            $entry[3] = $edit->reason;
                            array_push($pangs, $entry);
                            $entry = [];
                            $day->difference -= $edit->quantity;
                        }
                    }
                    if ($day->difference > 0) {
                        $entry[0] = $day->day;
                        $entry[1] = $day->difference;
                        $entry[3] = "Temps de présence avant " . $settings->morning_start . " et / ou après " . $settings->afternoon_extra;
                        array_push($pangs, $entry);
                    } elseif ($day->difference < 0) {
                        $entry[0] = $day->day;
                        $entry[1] = $day->difference;
                        if ($day->difference > (-1 * $settings->absent_loss)) {
                            $entry[3] = "Retard";
                        } else {
                            $entry[3] = "Absence a une (demi-)journée";
                        }
                        array_push($pangs, $entry);
                    }
                }
            }
            $student->pangs = $pangs;
            $student->pangsHistory = $pangsHistory;
            $student->attendanceHistory = $attendanceHistory;
            return view("students.show", compact("student", "days"));
        }
        else
        {
            return redirect('/');
        }
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

    public function checkIn(Request $request)
    {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $request->input("id"))
            ->update(["arrived_at" => $date->toTimeString()]);
        ProcessPangs::dispatch();
        echo $date->toTimeString();
    }

    public function checkOut(Request $request)
    {
        $date = Carbon::now("Europe/Paris");
        Day::where("day", $date->toDateString())
            ->where("student_id", $request->input("id"))
            ->update(["leaved_at" => $date->toTimeString()]);
        ProcessPangs::dispatch();
        echo $date->toTimeString();
    }
}
