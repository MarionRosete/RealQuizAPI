<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validInput = $request->validate([
            'name'=>'required|max:50',
            'description'=>'required|max:100',
            'owner' => 'required'
        ]);

        $validInput['id']=Str::uuid();
        $quiz = Quiz::create($validInput);
        return["quiz"=>$quiz];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuizRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuizRequest $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        $id = Auth::id();
        $quiz = Quiz::where('owner',$id)->get();
        return ["quiz"=>$quiz];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuizRequest  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id=$request->id;
         $quiz = Quiz::findOrFail($id);
         $quiz->name = $request->name;
         $quiz->description = $request->description;
         $quiz->save();
         return ['quiz'=>$quiz, 'msg'=>'Successfully updated' . $quiz->name];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */

     public function delete(Request $request)
     {
         //
         $id=$request->id;
         $quiz = Quiz::findOrFail($id);
         $quiz->delete();
         return ['quiz'=>$quiz, 'msg'=>'Successfully deleted' . $quiz->name];
     }
}