<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoList;
use Illuminate\Support\Facades\DB;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todolists = TodoList::where('user_id', auth()->id())->get();
        return view('pages.todo-list',[
            'todolists' => $todolists,
        ]);
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
        $task = TodoList::create([
            "body" => $request->body,
            "user_id" => auth()->id(),
        ]);
        return[
            'status' => 'success',
            'message' => 'New task created',
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = TodoList::where('id', $id)->first();

        return [
            'status' => 'success',
            'task' => $task,
            'message' => 'retrieved todolist data',
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $task = TodoList::where('id', $id)->first();
        if(!$task){
            return [
                'status' => 'error',
                'message' => 'Task not found',
            ];
        }
        else {
            $task->update([
                "body" => $request->body,
            ]);
            return [
                'status' => 'success',
                'message' => 'Task updated',
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    public function setIsComplete(Request $request, $id)
    { 
        $task = TodoList::where('id', $id)->first();
        if(!$task){
            return [
                'status' => 'error',
                'message' => 'Task not found',
            ];
        }
        else {
            $task->update([
                "is_complete" => ($request->is_complete == 0) ? 1 : 0,
            ]);
            return [
                'status' => 'success',
                'message' => 'Task updated',
            ];
        }
    }
    public function getTodoListAPI(Request $request){
        if ($request->user_id == '' || $request->user_id == null){
            return response()->json([
                'status' => 'error',
                'message' => 'user_id not supplied',
            ]);
        }
        else{
            $tasks = DB::table('todo_lists')
            ->selectRaw('id, body, is_complete, user_id')->get();
            return response()->json([
                'todos' => $tasks,
                'status' => '200',
                'message' => 'Todo list successfully retrieved',
            ]);
        }
    }
}
