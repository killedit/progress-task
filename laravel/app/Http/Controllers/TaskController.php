<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignedUser', 'createdByUser']);

        $userId = null;

        $authHeader = $request->header('Authorization');

        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);

            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken && $accessToken->tokenable) {
                $user = $accessToken->tokenable;
                $userId = $user->id;
            }
        }

        if ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('tasks.assigned_to', $userId)
                  ->orWhere('tasks.created_by', $userId);
            });
        }

        return $query->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        // $data['created_by'] = Auth::id() ?? null;
        $data['created_by'] = auth()->id();
        $data['is_completed'] = false;

        $task = Task::create($data);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function create(Task $task)
    {
        $users = User::all();
        return view('tasks.form', compact('users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'is_completed' => 'boolean',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = Carbon::parse($validated['due_date'])->format('Y-m-d H:i:s');
        }

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task->load(['assignedUser', 'createdByUser']),
        ]);
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
