<?php

use App\Project;
use App\ProjectDescription;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;



class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {

        // We'll be adding projects to every user
        foreach (User::all() as $user) {
            $projects = factory(Project::class, 3)->create([
                'user_id' => $user->id
            ])->each(function ($project) use ($faker) {

                factory(ProjectDescription::class, 1)->create([
                    'project_id' => $project->id,
                    'order' => 0,
                ])->each(function ($description) {
                    Project::find($description->project_id)->descriptions()->save($description);
                });

                factory(ProjectDescription::class, 1)->create([
                    'project_id' => $project->id,
                    'order' => 1,
                    'type' => 'image_left',
                    'image_id' => factory('App\File')->create([
                        'name' => 'Project image',
                        'path' =>  $faker->imageUrl(700, 500, 'cats',true)
                    ])->id
                ])->each(function ($description) {
                    Project::find($description->project_id)->descriptions()->save($description);
                });;

                factory(ProjectDescription::class, 1)->create([
                    'project_id' => $project->id,
                    'order' => 2,
                ])->each(function ($description) {
                    Project::find($description->project_id)->descriptions()->save($description);
                });;

                factory(ProjectDescription::class, 1)->create([
                    'project_id' => $project->id,
                    'order' => 3,
                    'type' => 'image_right',
                    'image_id' => factory('App\File')->create([
                        'name' => 'Project image',
                        'path' =>  $faker->imageUrl(700, 500, 'cats',true)
                    ])->id
                ])->each(function ($description) {
                    Project::find($description->project_id)->descriptions()->save($description);
                });;

                factory(ProjectDescription::class, 1)->create([
                    'project_id' => $project->id,
                    'order' => 4,
                ])->each(function ($description) {
                    Project::find($description->project_id)->descriptions()->save($description);
                });;
            });

        }

    }
}
