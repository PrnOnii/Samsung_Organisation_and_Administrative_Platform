<?php

namespace App\Http\Controllers;

use App\pang;
use Illuminate\Http\Request;

class PangController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\pang  $pang
     * @return \Illuminate\Http\Response
     */
    public function show(pang $pang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\pang  $pang
     * @return \Illuminate\Http\Response
     */
    public function edit(pang $pang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\pang  $pang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pang $pang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\pang  $pang
     * @return \Illuminate\Http\Response
     */
    public function destroy(pang $pang)
    {
        //
    }
}
