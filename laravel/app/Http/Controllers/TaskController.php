<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
// dd("here");
        Log::info('Tasks API called');

        // If user logged in, show their tasks only; otherwise all
        if (Auth::check()) {
            return Task::where('assigned_to', Auth::id())->paginate(10);
        }

        return Task::paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $data['assigned_by'] = Auth::id() ?? null;
        $data['is_completed'] = false;

        $task = Task::create($data);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->only(['title', 'description', 'due_date', 'is_completed']);
        $task->update($data);

        return response()->json(['message' => 'Task updated successfully.']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function complete(Task $task)
    {
        $task->is_completed = !$task->is_completed;
        $task->save();
        return response()->json([
            'message' => $task->is_completed ? 'Task marked complete' : 'Task marked incomplete',
            'task' => $task
        ]);
    }

}
