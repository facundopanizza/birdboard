<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        // dd('si');
        $this->authorize('update', $project);

        request()->validate(['body' => ['required']]);
        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $validated = request()->validate(['body' => ['required']]);

        if(request('completed')) {
            $task->complete();
        } else {
            $task->incomplete();
        }

        $task->update([
            'body' =>  $validated['body']
        ]);

        return redirect($project->path());
    }
}
