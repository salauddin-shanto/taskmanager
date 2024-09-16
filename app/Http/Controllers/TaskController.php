<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');  
        $sort_by_date = $request->query('sort_by_date', 'asc'); 

        $query = Task::query();

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy('due_date', $sort_by_date);

        $tasks = $query->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:Pending,In Progress,Completed',
            'due_date' => 'required|date',
        ]);
    
        $task = Task::create($validated);
    
        return response()->json($task, 201); 
    }
    

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed',
            'due_date' => 'required|date',
        ]);

        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
