<?php

namespace App\Http\Controllers\api\v1\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tasks extends Controller
{
    function listTasks()
    {
        $userTasks = Task::where('user_id', Auth::id())->paginate(15);
        if ($userTasks->count() > 0) {
            return response(['tasks' => $userTasks], 200);
        } else {
            return response('No task found!', 200);
        }
    }

    function addTask(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|string',
            'description' => 'string',
            'complete_by' => 'date|date_format:Y-m-d H:i:s|after_or_equal:today',
        ]);

        $newTask = new Task();
        $newTask->user_id = Auth::id();
        $newTask->title = $request->input('title');
        $newTask->description = $request->input('description', null);
        $newTask->complete_by = $request->input('complete_by', null);
        $newTask->save();
        if (!empty($newTask->id)) {
            return response(['tasks', $newTask], 200);
        }
        return response("Couldn't create a new tasks", 500);
    }

    function editTask(Task $task, Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|string',
            'description' => 'string',
            'complete_by' => 'date|date_format:Y-m-d H:i:s|after_or_equal:today',
        ]);
        if ($task->user_id === Auth::id()) {
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->complete_by = $request->input('complete_by');
            $task->update();
            return response(['task' => $task], 200);
        }
        return response("You don't have permissions to edit or modify this specific task", 401);
    }

    function deleteTask(Task $task)
    {
        if ($task->user_id === Auth::id()) {
            $task->delete();
            return response('Task was deleted', 200);
        }
        return response("You don't have permissions to edit or modify this specific task", 401);
    }

    public function markComplete(Task $task)
    {
        if ($task->user_id === Auth::id()) {
            $task->completed_at = new \DateTime();
            $task->save();
            return response("Task completed", 200);
        }
        return response("You don't have permissions to edit or modify this specific task", 401);
    }

    public function unMarkComplete(Task $task)
    {
        if ($task->user_id === Auth::id()) {
            $task->completed_at = null;
            $task->save();
        }
        return response("You don't have permissions to edit or modify this specific task", 401);
    }
}
