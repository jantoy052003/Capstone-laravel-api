<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Task;
use App\Models\Deleted_Task;
use App\Models\Completed_Task;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskShowResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($task_id = null)  // from Request $request to $task_id = null
    {
        // $order = $request->query('order') ? $request->query('order') : 'asc'; //we can change the order from oldest task/upcoming tasks
        
        // return TaskResource::collection(Task::select('id', 'user_id', 'task_title', 'task_body', 'task_date', 'created_at', 'updated_at')
        //     ->orderBy('task_date', $order)
        //     ->paginate(5));

        if ($task_id) {
            $task = Task::where('id', $task_id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail(['id', 'task_title', 'task_start', 'task_end', 'task_body']);
            
            return response([
                'task' => $task
            ], 200);
        }
        
        $tasks = Task::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'task_title', 'task_start', 'task_end', 'task_body']);

        return response([
            'tasks' => $tasks
        ], 200);
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
        // $fields = $request->validate([
        //     'task_title' => 'required | string',
        //     'task_body' => 'required | string',
        //     'task_date' => 'required',
        //     'image' => 'nullable|string'
        // ]);
    
        // $task = Task::create([
        //     'user_id' => auth()->user()->id,
        //     'task_title' => $fields['task_title'],
        //     'task_body' => $fields['task_body'],
        //     'task_date' => $fields['task_date'],
        //     'image' => $fields['image'],
        // ]);
        //     return response($task, 201);
        // return Task::create($request->all()); //this was used for testing without relationships

        $fields = $request->validate([
            'task_title' => 'required|string',
            'task_body' => 'required|string',
            'task_start' => 'nullable|date_format:Y-m-d',
            'task_end' => 'nullable|date_format:Y-m-d',
        ]);

        $task = Task::create([
            'user_id' => auth()->user()->id,
            'task_title' => $fields['task_title'],
            'task_body' => $fields['task_body'],
            'task_start' => $fields['task_start'],
            'task_end' => $fields['task_end'],
        ]);

        return response([
            'message' => 'Task has been created'
        ], 200);
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
        // $fields = $request->validate([
        //     'task_title' => 'required | string',
        //     'task_body' => 'required | string',
        //     'task_date' => 'required',
        //     'image' => 'nullable|string'
        // ]);

        // $task = Task::find($id);
        // $task->update($request->all());

        // return response($task, 200);

        $task = Task::where('id', $id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

        $fields = $request->only(['task_title', 'task_body', 'task_start', 'task_end']);

        if (empty(array_filter($fields))) {
            return response([
                'message' => 'Cannot update empty fields'
            ], 400);
        }

        $task->update([
            'task_title' => $fields['task_title'] ? $fields['task_title'] : $task->task_title,
            'task_body' => $fields['task_body'] ? $fields['task_body'] : $task->task_body,
            'task_start' => $fields['task_start'] ? $fields['task_start'] : $task->task_start,
            'task_end' => $fields['task_end'] ? $fields['task_end'] : $task->task_end,
        ]);
        
        return response([
            'message' => 'Task has been updated successfully'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Task::destroy($id);

        // $response = [
        //     'message'=> "Task deleted"
        // ];

        // return response($response, 200);
        
        $task = Task::where('id', $id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

        $deletedTask = new Deleted_Task;
        $deletedTask->task_title = $task->task_title;
        $deletedTask->task_body = $task->task_body;
        $deletedTask->task_start = $task->task_start;
        $deletedTask->task_end = $task->task_end;
        $deletedTask->user_id = auth()->user()->id;
        $deletedTask->save();

        $task->delete();

        return response([
            'message' => 'Task has been deleted.'
        ], 200);
    }

    public function getUserTasks(string $id) 
    {
        $tasks = Task::where('user_id', $id)->orderBy('task_date', 'desc')->paginate(5);

        return TaskResource::collection($tasks);

    }   
}
