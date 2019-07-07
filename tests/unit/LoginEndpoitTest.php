<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginEndpoitTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        factory(User::class, 1)->create();
    }

    public function testTheFactoryCreatedOneUser() {
        $numberOfExpectedUsers = 1;
        $numberofActualUsers = sizeof(User::all());

        $this->assertEquals($numberOfExpectedUsers, $numberofActualUsers);
    }

    public function testSuccesfullLoginReturnsTokenWhichCanBeTurnedIntoUser() {

        $testUser = User::Find(1);

        $response = $this->json('POST', '/api/login/token', [
            'email' => $testUser->email,
            'password' => 'secret'
        ]);

        $content = $response->content();
        $recievedToken = substr($content, 17, -2);

        $loggedInUser = JWTAuth::toUser($recievedToken);
        $this->assertEquals($testUser, $loggedInUser);

    }

    public function testInsuccesfullLoginReturnsErrorMessage() {

        $testCredentials = [
            "email" => "test@test.com",
            "password" => 'notsosecret'
        ];

        $response = $this->json('POST', '/api/login/token', $testCredentials);

        $response->assertExactJson([
            "message" => "Incorrect password or email.",
        ]);

    }


}
