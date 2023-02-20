<?php

namespace App\Http\Controllers;

use App\Models\QandA;
use Illuminate\Http\Request;

class QandAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return $request->user();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        foreach ($data as $key ) {
            QandA::create($key);
        }
       
        return ["msg"=>"success"];
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
     * @param  \App\Models\QandA  $qandA
     * @return \Illuminate\Http\Response
     */
    public function show(QandA $qandA)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QandA  $qandA
     * @return \Illuminate\Http\Response
     */
    public function edit(QandA $qandA)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QandA  $qandA
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QandA $qandA)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QandA  $qandA
     * @return \Illuminate\Http\Response
     */
    public function destroy(QandA $qandA)
    {
        //
    }
}
