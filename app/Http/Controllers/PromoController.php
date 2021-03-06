<?php

namespace App\Http\Controllers;

use App\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("promo.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Promo::create([
            "name" => $request->input("name"),
        ]);
        return redirect("/student");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function show(promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function edit(promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, promo $promo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function destroy(promo $promo)
    {
        //
    }
}
