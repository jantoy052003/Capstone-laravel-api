<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskShowResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = $request->query('order') ? $request->query('order') : 'asc'; //we can change the order from oldest task/upcoming tasks
        
        return TaskResource::collection(Task::select('id', 'user_id', 'task_title', 'task_body', 'task_date', 'created_at', 'updated_at')
            ->orderBy('task_date', $order)
            ->paginate(5));
    }

    /**
     * Search for the task title
     */
    public function search(Request $request) { 
        $order = $request->query('order') ? $request->query('order') : 'desc';
        $search_term = '%' . $request->query('term') . '%';
        return TaskResource::collection(Task::where('task_title', 'like', $search_term)
        ->orderBy('task_date', $order)
        ->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $fields = $request->validate([
            'task_title' => 'required | string',
            'task_body' => 'required | string',
            'task_date' => 'required',
            'image' => 'nullable|string'
        ]);
    
        $task = Task::create([
            'user_id' => auth()->user()->id,
            'task_title' => $fields['task_title'],
            'task_body' => $fields['task_body'],
            'task_date' => $fields['task_date'],
            'image' => $fields['image'],
        ]);
            return response($task, 201);
        // return Task::create($request->all()); //this was used for testing without relationships
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(TaskShowResource::make(Task::find($id)), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $fields = $request->validate([
            'task_title' => 'required | string',
            'task_body' => 'required | string',
            'task_date' => 'required',
            'image' => 'nullable|string'
        ]);

        $task = Task::find($id);
        $task->update($request->all());

        return response($task, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Task::destroy($id);

        $response = [
            'message'=> "Task deleted"
        ];

        return response($response, 200);
    }

    public function getUserTasks(string $id) {
        $tasks = Task::where('user_id', $id)->orderBy('task_date', 'desc')->paginate(5);

        return TaskResource::collection($tasks);
    }   
}
