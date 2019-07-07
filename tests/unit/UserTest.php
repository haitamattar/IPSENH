<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(){
        parent::setup();
        $this->artisan('db:seed',['--class'=>'UsersTableSeeder']);
    }

    public function testUserInstance(){
        $user = new User;

        $this->assertInstanceOf(User::class,$user);
    }
}