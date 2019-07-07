<?php

namespace Tests\Unit;

use App\UserSkill;
use Tests\TestCase;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSkillSeederTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(){
        parent::setup();
        $this->artisan('db:seed',['--class'=>'UsersTableSeeder']);
        $this->artisan('db:seed',['--class'=>'UserSkillTableSeeder']);
    }

    public function testUserSkillSeederSeedsMoreThan50(){
        $expectedValue = 50;
        $actualValue = UserSkill::all()->count();
        $this->assertTrue($expectedValue<=$actualValue,$message=(string)$actualValue);
    }
}