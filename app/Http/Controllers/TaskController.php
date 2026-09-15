<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    // 1. READ (List user tasks)
    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->get();
        return Inertia::render('Tasks/Index', ['tasks' => $tasks]);
    }

    // 2. CREATE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->tasks()->create($request->only('title', 'description'));

        return redirect()->back();
    }

    // 3. UPDATE
    public function update(Request $request, Task $task)
    {
        $this->authorizeUser($task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($request->only('title', 'description'));

        return redirect()->back();
    }

    // 4. DELETE
    public function destroy(Task $task)
    {
        $this->authorizeUser($task);
        $task->delete();

        return redirect()->back();
    }

    private function authorizeUser(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
    }
}