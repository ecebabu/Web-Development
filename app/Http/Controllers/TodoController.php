<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Todo;
use App\Http\Resources\todo as TodoResource;
class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get todo
        $todo = Todo::paginate(10);

        //Return the collection of todos as a resource
        return TodoResource::collection($todo);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = $request->isMethod('put') ? Todo::findOrFail($request->todo_id) : new Todo;

        $todo->id = $request->input('todo_id');
        $todo->title = $request->input('title');
        $todo->description = $request->input('description');

        if($todo->save()) {

            return new TodoResource($todo);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Get todo
        $todo = Todo::findOrFail($id);

        //Return a single todo as a resource
        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $todo = Todo::findOrFail($id);

       if($todo->delete()) {
           return new TodoResource($todo);

       }
        
    }
}
