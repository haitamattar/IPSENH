<?php

namespace Tests\Unit;

use App\Skill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkillSeederTest extends TestCase
{
    use RefreshDatabase;
    public function testSkillSeederAllSkills(){
        $expectedValue = 15;
        $actualValue = Skill::all()->count();

        $this->assertEquals($expectedValue,$actualValue);
    }
}