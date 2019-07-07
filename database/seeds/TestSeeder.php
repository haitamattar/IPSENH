<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the test seeds, only run this seeder for testing purpose.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('file')->delete();
        DB::table('project')->delete();
        DB::table('following')->delete();


        $this->call([
            UsersTableSeeder::class,
            ProjectSeeder::class,
            FollowingTableSeeder::class,
            UserSkillTableSeeder::class
        ]);
    }
}
