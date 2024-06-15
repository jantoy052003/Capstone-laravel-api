<?php

namespace App\Http\Controllers;

use App\Models\Completed_Task;
use App\Models\Task;
use Illuminate\Http\Request;

class CompletedTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $completed_task = Completed_Task::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'task_title', 'task_body', 'task_start', 'task_end', 'completed_at']);

        return response([
            'completed_task' => $completed_task,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'task_title'   => 'required|string',
            'task_body'    => 'required|string',
            'task_start'   => 'nullable|date_format:Y-m-d',
            'task_end'     => 'nullable|date_format:Y-m-d',
            'completed_at' => 'nullable|date_format:Y-m-d',
        ]);

        $completed_task = Completed_Task::create([
            'user_id'      => auth()->user()->id,
            'task_title'   => $fields['task_title'],
            'task_body'    => $fields['task_body'],
            'task_start'   => $fields['task_start'],
            'task_end'     => $fields['task_end'],
            'completed_at' => $fields['completed_at'],
        ]);
    }

    /**
     * Completes a task base on ID.
     */
    public function complete(string $id)
    {
        $task = Task::where('id', $id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

        $deletedTask = new Completed_Task();
        $deletedTask->task_title = $task->task_title;
        $deletedTask->task_body = $task->task_body;
        $deletedTask->task_start = $task->task_start;
        $deletedTask->task_end = $task->task_end;
        $deletedTask->user_id = auth()->user()->id;
        $deletedTask->completed_at = now();
        $deletedTask->save();

        $task->delete();

        return response([
            'message' => "Congratulations! You've completed your task. Keep up the great work!",
        ], 200);
    }

    /**
     * Permanently delete a task base on ID.
     */
    public function delete(string $id)
    {
        $deleted_task = Completed_Task::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $deleted_task->forceDelete();

        return response([
            'message' => 'The achievement deleted successfully.',
        ], 200);
    }

    /**
     * Permanently delete all completed task at once.
     */
    public function completeAll()
    {
        Completed_Task::where('user_id', auth()->user()->id)->forceDelete();

        return response([
            'message' => 'All achievements permanently deleted successfully.',
        ], 200);
    }
}
