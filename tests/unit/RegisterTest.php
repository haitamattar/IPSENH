<?php

namespace Tests\Unit;

use App\User;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    private function mockSocialiteFacade($providerName, $email = 'foo@bar.com', $token = 'foo', $id = 1) {

        $socialiteUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $socialiteUser->id = $id;
        $socialiteUser->token = $token;
        $socialiteUser->name = "Firstname Lastname";
        $socialiteUser->email = $email;

        $provider = $this->createMock('\Laravel\Socialite\Two\\' . $providerName .  'Provider');


        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        $this->app->instance(Socialite::class, $stub);

    }

    public function testRegisterEndpointRedirectsToGithubLogin() {
        $response = $this->call('GET', '/auth/register/github');

        $locationHeader = $response->headers->all()['location'][0];

        $this->assertContains('github.com/login/oauth/authorize', $locationHeader);

    }

    public function testItRetrievesTheGithubRequestAndCreatesANewUser() {
        $testEmail = 'test_github_user@example.com';
        $this->mockSocialiteFacade('Github', $testEmail);

        $this->get('/auth/callback/github');
        $registeredGithubUser =  User::where('email', $testEmail)->first();

        $this->assertInstanceOf(User::class, $registeredGithubUser);
        $this->assertTrue(isset($registeredGithubUser->github_id));

    }

    public function testRegisterEndpointRedirectsToGitlabLogin() {
        $response = $this->call('GET', '/auth/register/gitlab');

        $locationHeader = $response->headers->all()['location'][0];

        $this->assertContains('gitlab.com/oauth/authorize', $locationHeader);
    }

    public function testItRetrievesTheGitlabRequestAndCreatesANewUser() {
        $testEmail = 'test_gitlab_user@example.com';
        $this->mockSocialiteFacade('Gitlab', $testEmail);

        $this->get('/auth/callback/gitlab');
        $registeredGitlabUser =  User::where('email', $testEmail)->first();

        $this->assertInstanceOf(User::class, $registeredGitlabUser);
        $this->assertTrue(isset($registeredGitlabUser->gitlab_id));

    }

    public function testRegisterEndpointRedirectsToBitbucketLogin() {
        $response = $this->call('GET', '/auth/register/bitbucket');

        $locationHeader = $response->headers->all()['location'][0];

        $this->assertContains('bitbucket.org/site/oauth2/authorize', $locationHeader);
    }

    public function testItRetrievesTheBitbucketRequestAndCreatesANewUser() {
        $testEmail = 'test_bitbucket_user@example.com';
        $this->mockSocialiteFacade('Bitbucket', $testEmail);

        $this->get('/auth/callback/bitbucket');
        $registeredGitlabUser =  User::where('email', $testEmail)->first();

        $this->assertInstanceOf(User::class, $registeredGitlabUser);
        $this->assertTrue(isset($registeredGitlabUser->bitbucket_id));

    }


    public function testRegisteringWithNotSupportedProviderReturnsAnErrorMessage() {
        $response = $this->call('GET', '/auth/register/thisproviderisobviousplynotsupporteda');

        $response->assertExactJson([
            "error" => "This provider is not supported.",
        ]);
    }

}
