<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Resources\TaskResource;

/**
 * @OA\Info(
 *      title="Progress Task API",
 *      version="1.0.0",
 *      description="Documentation for Progress Task REST API"
 * ),
 * @OA\Server(
 *      url="http://127.0.0.1:8007",
 *      description="Local server"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
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

        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $tasks = $query
            ->orderBy('created_at', 'asc')
            ->paginate($perPage)
            ->appends($request->query());
        return TaskResource::collection($tasks)
            ->response()
            ->setStatusCode(200)
        ;
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     security={{"bearerAuth":{}}},
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Example task"),
     *             @OA\Property(property="description", type="string", example="Example task description"),
     *             @OA\Property(property="due_date", type="string", example="2025-10-20, 15:30:00"),
     *             @OA\Property(property="assigned_to", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=9),
     *             @OA\Property(property="title", type="string", example="Example task"),
     *             @OA\Property(property="description", type="string", example="Example task description"),
     *             @OA\Property(property="due_date", type="string", example="2025-10-20 15:30:00"),
     *             @OA\Property(property="assigned_to", type="integer", example=2),
     *             @OA\Property(property="created_by", type="integer", example=1),
     *             @OA\Property(property="is_completed", type="boolean", example=false),
     *             @OA\Property(property="created_at", type="string", example="2025-11-12T14:28:10.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2025-11-12T14:28:10.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The title field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|string',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $data['created_by'] = Auth::id();
        $data['is_completed'] = false;

        if (!empty($data['due_date'])) {
            try {
                $data['due_date'] = Carbon::parse($data['due_date'])->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Invalid due_date format.'
                ], 422);
            }
        } else {
            $data['due_date'] = null;
        }

        $task = Task::create($data);

        return new TaskResource($task);
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
