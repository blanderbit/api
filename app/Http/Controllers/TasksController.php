<?php

namespace App\Http\Controllers;
use App\User;
use App\Task;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class TasksController extends Controller
{
    public function addTaskProjectUser (Request $request, $id, $id_project)
    {
        $task = new Task();
        $task->project_id = $id_project;
        $task->name_task = $request->get('name_task');
        $task->description = $request->get('text_task');
        $task->deadline = $request->get('deadline');
        $task->status_task = 1;
    }

    public function getTaskProjectUser (Request $request, $id, $id_project)
    {

    }

    public function updateTaskProjectUser (Request $request, $id, $id_project, $id_task)
    {

    }

    public function removeTaskProjectUser (Request $request, $id, $id_project, $id_task)
    {

    }
}
