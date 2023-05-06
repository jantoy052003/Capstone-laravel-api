<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Task;
use App\Models\Deleted_Task;

class DeletedTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deleted_task = Deleted_Task::where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'task_title', 'task_body', 'task_start', 'task_end']);

        return response([
            'deleted_task' => $deleted_task
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'task_title' => 'required|string',
            'task_body' => 'required|string',
            'task_start' => 'nullable|date_format:Y-m-d',
            'task_end' => 'nullable|date_format:Y-m-d',
        ]);

        $deleted_task = Deleted_Task::create([
            'user_id' => auth()->user()->id,
            'task_title' => $fields['task_title'],
            'task_body' => $fields['task_body'],
            'task_start' => $fields['task_start'],
            'task_end' => $fields['task_end'],
        ]);
    }

    /**
     * Restore a task base on ID.
     */
    public function restore(string $id) {
        $deleted_task = Deleted_Task::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $task = new Task([
            'user_id' => $deleted_task->user_id,
            'task_title' => $deleted_task->task_title,
            'task_body' => $deleted_task->task_body,
            'task_start' => $deleted_task->task_start,
            'task_end' => $deleted_task->task_end,
        ]);

        $task->save();
        $deleted_task->delete();

        return response([
            'message' => 'Task restored successfully.'
        ], 200);
    }

    /**
     * Permanently delete a task base on ID.
     */
    public function delete(string $id)  {
        $deleted_task = Deleted_Task::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $deleted_task->forceDelete();

        return response([
            'message' => 'Task permanently deleted successfully.'
        ], 200);
    }

    /**
     * Restore all task at once.
     */
    public function restoreAll() {
        Deleted_Task::where('user_id', auth()->user()->id)->get()->each(function ($deleted_task) {
            $task = new Task([
                'user_id' => $deleted_task->user_id,
                'task_title' => $deleted_task->task_title,
                'task_body' => $deleted_task->task_body,
                'task_start' => $deleted_task->task_start,
                'task_end' => $deleted_task->task_end,
            ]);

            $task->save();
            $deleted_task->delete();
        });

        return response([
            'message' => 'All tasks restored successfully.'
        ], 200);
    }

    /**
     * Permanently delete all task at once.
     */
    public function deleteAll() {
        Deleted_Task::where('user_id', auth()->user()->id)->forceDelete();

        return response([
            'message' => 'All tasks permanently deleted successfully.'
        ], 200);
    }
}
