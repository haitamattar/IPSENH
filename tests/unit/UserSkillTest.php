<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\UserSkill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSkillTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(){
        parent::setup();
        $this->artisan('db:seed',['--class'=>'UsersTableSeeder']);
    }

    public function testUserSkillInstance(){
        $skill = new UserSkill;

        $this->assertInstanceOf(UserSkill::class,$skill);
    }

    public function testUserSkillUser1HaSkill2WithNameCpp(){
        $skill = new UserSkill;

        $skill->user_id=1;
        $skill->skill_id=2;
        $skill->save();

        $expectedValue = 'C++';
        $actualValue =  $skill->skill->name;

        $this->assertEquals($expectedValue,$actualValue);
    }
}