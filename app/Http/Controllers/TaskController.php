<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Show all tasks
    public function index()
    {
        $tasks = Task::where('status','!=','Done')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function showAll(){
        $tasks = Task::all();
        return response()->json(['Status' => 'OK','Message' => 'Data Inserted','task' => $tasks]);
    }

    // Store new task
    public function store(Request $request)
    {
        $request->validate(['task' => 'required']);
        $taskExist = Task::where('task',$request->get('task'))->first(['id']);

        if(isset($taskExist->id)){
            return response()->json(['Status' => 'FAIL','Message' => 'Data Already Present']);
        }

        $task =new Task();
        $task->task = $request->get('task');
        $task->status = 'Pending';
        $task->save();

        return response()->json(['Status' => 'OK','Message' => 'Data Inserted','id' => $task->id,'task' => $request->get('task'),'status' => 'Pending']);
    }

    // Update task status
    public function update($id)
    {
        $task = Task::find($id);
        $task->status = 'Done';
        $task->save();
        return redirect()->back();
    }

    // Delete task
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect()->back();
    }
}

