<?php

namespace App\Http\Controllers\Project;

use App\ProjectExternalUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\File;
use App\ProjectDescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{


    protected function store(Request $request)
    {
//        dd($request->all());
//        $validatedData = $request->validate([
//            'name' => 'required|max:30',
//            'descriptions' => 'required',
//            'descriptions.*.order' => 'required|numeric',
//            'descriptions.*.description' => 'required'
//        ]);


        $user = auth()->user();

        $project = $this->storeProject($request, $user);
        $this->saveHeaderImage($request, $project);
        $this->storeProjectDescriptions($request, $project);

        if(!empty($request['external'])) {
            $this->storeProjectExternalUrl($request, $project);
        }

        return response()->json([
            'message' => "Project created successfully"
        ]);

    }

    private function saveHeaderImage(Request $request, Project $project) {
        $data = base64_decode(explode(';base64,', $request['header_image'])[1]);

        $im = new \Imagick();
        $im->readimageblob($data);
        $im->setImageFormat('png');
        $name = 'public/images/projectImages/' . uniqid() . '.png';
        Storage::disk('local')->put($name, $im);
        $path = Storage::url($name);

        $file = File::create([
            'name' => 'Project ' . $project->id . ' header',
            'path' => $path,
        ]);

        $project->header_image_id = $file->id;
        $project->save();
    }

    private function storeProject(Request $request, $user) {
        return Project::create([
            'user_id'           => $user->id,
            'name'              => $request['name'],
            'description'       => $request['description'],
            'header_image_id'   => 1, // TODO: update
        ]);
    }

    private function storeProjectDescriptions(Request $request, Project $project) {
        foreach ($request['descriptions'] as $description) {
            $imageId = null;
            if(isset($description['image'])) {
                $data = base64_decode(explode(',', $description['image'])[1]);
                $im = new \Imagick();
                $im->readimageblob($data);
                $im->setImageFormat('png');
                $name = 'public/images/projectImages/' . uniqid() . '.png';
                Storage::disk('local')->put($name, $im);
                $path = Storage::url($name);

                $file = File::create([
                    'name' => 'Project ' . $project->id . ' description ' . uniqid(),
                    'path' => $path,
                ]);

                $imageId = $file->id;

            }

            ProjectDescription::create([
                'project_id'    => $project['id'],
                'order'         => $description['order'],
                'type'          => $description['type'],
                'title'         => $description['title'],
                'description'   => $description['description'],
                'image_id'      => $imageId
            ]);
        }
    }

    private function storeProjectExternalUrl(Request $request, Project $project) {
        ProjectExternalUrl::create([
            'project_id'        => $project['id'],
            'provider_name'     => $request['external']['provider_name'],
            'external_id'       => $request['external']['external_id'],
            'name'              => $request['external']['name'],
            'url'               => $request['external']['url'],
        ]);
    }


    protected function show()
    {
        $project = Project::all();

        return $project;
    }

    protected function getProjectById(Project $project) {
        return response()->json([
            'user' => $project->user()->with('information', 'information.image:id,path')->first(),
            'project' => $project
        ]);
    }
}
