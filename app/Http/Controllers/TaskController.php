<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return $tasks;
    }

    /**
     * creates a new task
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'description' => 'max:250',
            'priority' => 'required',
            'due_date_time' => 'required',
            'assigned_user' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => 'Task data validation error', 'error' => $validation->messages()], 422);
        } else {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => Task::TODO,
                'priority' => $request->priority,
                'due_date_time' => $request->due_date_time,
                'assigned_user' => $request->assigned_user,
            ]);
            if ($task->save()) {
                return response()->json(['success' => true, 'message' => 'Task created successfully', 'data' => ['task_id' => $task->id]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Error occured'], 500);
            }
        }
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
    public function update(Request $request, $task_id)
    {
        $rules = [
            'title' => 'required',
            'description' => 'max:250',
            'priority' => 'required',
            'due_date_time' => 'required',
            'assigned_user' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => 'Task data validation error', 'error' => $validation->messages()], 422);
        } else {
        $task = Task::where('id', $task_id)->first();

        if (empty($task)) {
            return response()->json(['success' => false, 'message' => 'Task does not exist'], 200);
        } else {
            $task->title = $request->title;
            $task->description = $request->description;
            // 
            // the updation of status can also be implementated using numbers such as
            // 0 represents todo
            // 1 represents inprogress
            // 2 represents done
            // 
            if ($request->status == 'todo') {
                $task->status = Task::TODO;
            } else if ($request->status == 'inprogress') {
                $task->status = Task::INPROGRESS;
            } else if ($request->status == 'done') {
                $task->status = Task::DONE;
            }
            $task->priority = $request->priority;
            $task->due_date_time = $request->due_date_time;
            $task->assigned_user = $request->assigned_user;
            if ($task->save()) {
                return response()->json(['success' => true, 'message' => 'Task updated successfully', 'data' => ['task_id' => $task->id]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Error occured'], 500);
            }
        }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($task_id)
    {
        $task = Task::where('id', $task_id)->first();
        if (empty($task)) {
            return response()->json(['success' => false, 'message' => 'Task does not exist'], 200);
        } else {
            $task->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted successfully'], 200);
        }
    }
}
