<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\User;
use Bitbucket\Client;
use Bitbucket\Exception\ClientErrorException;
use Bitbucket\Exception\RuntimeException;

class BitbucketController extends Controller {

    protected function getBitbucketUserInfoEndpoint(User $user) {
        try {

            $userInfo = $this->getBitbucketUserInfo($user);

            return response()->json([
                'user' => $userInfo
            ]);

        } catch (ClientErrorException $exception) {
            return $this->getResponseForException($exception);
        }
    }

    protected function getBitbucketUserRepositoriesEndpoint(User $user) {
        try {

            $repositories = $this->getBitbucketUserRepositories($user);

            return response()->json([
                'repositories' => $repositories
            ]);

        } catch (ClientErrorException $exception) {
            return $this->getResponseForException($exception);
        }
    }

    private function getResponseForException(ClientErrorException $exception) {
        if ('No token' === $exception->getMessage()) {
            return response()->json([
                'error' => 'User does not have a Bitbucket account connected.'
            ]);
        } else {
            return response()->json([
                'exception' => $exception->getMessage()
            ]);
        }
    }

    private function throwNoBitBucketTokenException() {
        throw new ClientErrorException("No token");
    }

    /**
     * @param User $user
     * @return array
     * @throws \Http\Client\Exception
     */
    protected function getBitbucketUserInfo(User $user) {
        $token = $user->bitbucket_api_token ?? $this->throwNoBitBucketTokenException();

        $client = new Client();
        $client->authenticate(Client::AUTH_OAUTH_TOKEN, $token);

        return $client->currentUser()->show();

    }

    /**
     * @param User $user
     * @return array
     * @throws \Http\Client\Exception
     */
    protected function getBitbucketUserRepositories(User $user) {
        $token = $user->bitbucket_api_token ?? $this->throwNoBitBucketTokenException();

        $client = new Client();
        $client->authenticate(Client::AUTH_OAUTH_TOKEN, $token);

        $username = $this->getBitbucketUserInfo($user)['username'];
        $repositoriesResult = $client->repositories()->users($username)->list();
        $repositories = [];

        foreach($repositoriesResult['values'] as $repository) {
            array_push($repositories, $repository);
        }

        return $repositories;

    }

}