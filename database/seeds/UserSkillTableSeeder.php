<?php

use Illuminate\Database\Seeder;

class UserSkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(App\User::all() as $user){
            $index = rand(1,10);
            for($i=0;$i<rand(1,5);$i++){
                $nextIndex = ($index+$i)%14;
                if($nextIndex!=0){
                    $skill = new App\UserSkill;
                    $skill->user_id=$user->id;
                    $skill->skill_id=$nextIndex;
                    $skill->save();
                }
            }
        }
    }
}
