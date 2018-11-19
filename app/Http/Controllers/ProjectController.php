<?php

namespace App\Http\Controllers;
use App\User;
use App\Project;
use App\Task;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class ProjectController extends Controller
{
    public function getProjectsUser(Request $request, $id)
    {
        $old_projects = Project::where('user_id', $id)->get();
        $projects = [];
        $count = count($old_projects);
        for ($i = 0; $i < $count; $i++){
            $data = $old_projects[$i];
            $tasks = Task::where('project_id', $old_projects[$i]->id)->get();
            $data->tasks = $tasks;
            array_push($projects, $old_projects[$i]);
        }
        return response()->json([
            'data' => $projects
        ], 200);
    }
    public function getProjectUser(Request $request, $id, $id_project)
    {
        $project = Project::where('user_id', $id)->get()->find($id_project);
        if($project == null){
            return response()->json([
                'message' => 'No such project',
            ]);
        }
        $tasks = Task::where('project_id', $id_project)->get();
        $project->tasks = $tasks;
        return response()->json([
            'project' => $project
        ]);
    }
    public function addProjectUser(Request $request, $id)
    {
        $request->validate([
            'project_name' => 'string|required',
        ]);

        $project = new Project();
        $project->user_id = $id;
        $project->project_name = $request->get('project_name');
        $project->color = $request->get('color') == null ? 'grey': $request->get('color');

        $project->save();
        return response()->json([
            'message' => 'Successfully created user!',
            'project' => $project
        ],200);
    }
    public function updateProjectUser(Request $request, $id , $id_project)
    {
        $old_project = Project::where('user_id', $id)->get()->find($id_project);
        $project = Project::where('user_id', $id)->get()->find($id_project);
        if($project == null){
            return response()->json([
                'message' => 'No such project',
            ]);
        }
        $project->project_name= $request->get('project_name') == null ? $old_project->name :$request->get('project_name');
        $project->color= $request->get('color') == null ? $old_project->nickname :$request->get('color');
        $project->update();
        return response()->json([
            'message' => 'Successfully updated project!',
            "profile" => $project
        ], 200);
    }
    public function removeProjectUser(Request $request, $id ,$id_project)
    {
        $project = Project::where('user_id', $id)->get()->find($id_project);
        if($project == null){
            return response()->json([
                'message' => 'No such project',
            ]);
        }
        $tasks = Task::where('project_id', $id_project)->get();
        $tasks->delete();
        $project->delete();
        return response()->json([
            'message' => 'Successfully remove project!',
        ], 200);
    }

}
