<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
                ->get(['id', 'task_title']);

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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
