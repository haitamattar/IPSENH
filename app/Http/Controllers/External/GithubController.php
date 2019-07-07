<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\User;
use Github\Client;
use Github\Exception\RuntimeException;


class GithubController extends Controller {

    protected function getGithubUserInfoEndpoint(User $user) {
        try {
            $userInfo = $this->getGithubUserInfo($user);

            return response()->json([
                'user' => $userInfo
            ]);

        } catch (RuntimeException $runtimeException) {
            return $this->getResponseForException($runtimeException);
        }
    }

    protected function getGithubUserRepositoriesEndpoint(User $user) {
        try {
            $repositories = $this->getGithubUserRepositories($user);

          return response()->json([
              'repositories' => $repositories
          ]);

        } catch (RuntimeException $runtimeException) {
            return $this->getResponseForException($runtimeException);
        }
    }

    protected function getGithubUserRepositoryEndpoint(User $user, $identifier) {
        try {

            if(filter_var($identifier, FILTER_VALIDATE_INT)) {
                $repository = $this->getGithubUserRepositoryById($user, $identifier);
            } else {
                $repository = $this->getGithubUserRepositoryByName($user, $identifier);
            }

            return response()->json([
                'repository' => $repository
            ]);

        } catch (RuntimeException $runtimeException) {
            return $this->getResponseForException($runtimeException);
        }
    }

    protected function getGithubUserRepositoryLanguagesEndpoint(User $user, $identifier) {
        try {
            $languages = $this->getRepositoryLanguages($user, $identifier);

            return response()->json([
                'languages' => $languages
            ]);

        } catch (RuntimeException $runtimeException) {
            return $this->getResponseForException($runtimeException);
        }
    }

    /**
     * @param RuntimeException $runtimeException
     * @return \Illuminate\Http\JsonResponse
     */
    private function getResponseForException(RuntimeException $runtimeException) {
        if ('No token' === $runtimeException->getMessage()) {
            return response()->json([
                'error' => 'User does not have a github account connected.'
            ]);
        } else if ('Not Found' === $runtimeException->getMessage()) {
            return response()->json([
                'error' => "Could not find repository"
            ]);
        }
    }

    private function throwNoGithubTokenException() {
        throw new RuntimeException("No token");
    }

    private function throwGithubNotFoundException() {
        throw new RuntimeException('Not Found');
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \RuntimeException
     */
    private function getGithubUserInfo(User $user)  {
        $token = $user->github_api_token ?? $this->throwNoGithubTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_HTTP_TOKEN);

        return $client->api('current_user')->show();

    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonRespons
     * @throws \RuntimeException
     */
    private function getGithubUserRepositories(User $user) {
        $token = $user->github_api_token ?? $this->throwNoGithubTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_HTTP_TOKEN);

        return $client->api('current_user')->repositories();

    }

    /**
     * @param User $user
     * @param string $name
     * @return mixed
     */
    private function getGithubUserRepositoryByName(User $user, string $name) {
        $token = $user->github_api_token ?? $this->throwNoGithubTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_HTTP_TOKEN);
        $userName = $this->getGithubUserInfo($user)['login'];

        $repo = $client->api('repos')->show($userName, $name);
        $stats = $client->api('repos')->statistics($userName, $name);
        $branches = sizeOf($client->api('repos')->branches($userName, $name));

        $repo['contributors'] = sizeof($stats);
        $repo['branches'] = $branches;

        return $repo;
    }

    private function getGithubUserRepositoryById(User $user, $id) {
        $repositores = $this->getGithubUserRepositories($user);

        $found = array_search($id, array_column($repositores, 'id'));

        if($found === false) {
            $this->throwGithubNotFoundException();
        }

        $name = $repositores[$found]['name'];

        return $this->getGithubUserRepositoryByName($user, $name);

    }

    private function getRepositoryLanguages(User $user, $identifier) {
        $token = $user->github_api_token ?? $this->throwNoGithubTokenException();

        if(filter_var($identifier, FILTER_VALIDATE_INT)) {
            $repository = $this->getGithubUserRepositoryById($user, $identifier);
        } else {
            $repository = $this->getGithubUserRepositoryByName($user, $identifier);
        }

        $client = new Client();
        $client->authenticate($token, Client::AUTH_HTTP_TOKEN);

        return array_keys($client->api('repos')->languages($repository['owner']['login'], $repository['name']));

    }

}
