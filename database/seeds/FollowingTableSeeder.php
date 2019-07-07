<?php

use Illuminate\Database\Seeder;
use App\Following;

class FollowingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(App\User::all() as $user){
            for($i=1;$i<=rand(1,49);$i++){
                $following_user_id=($user['id']+$i)%50;
                if($following_user_id!=0 && $following_user_id!=$user['id']){
                    $following = new App\Following;
                    $following->user_id=$user['id'];
                    $following->following_user_id=$following_user_id;
                    $following->save();
                }
            }
        }
    }
}
