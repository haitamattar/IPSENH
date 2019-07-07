<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\User;
use Gitlab\Client;
use Gitlab\Exception\RuntimeException;


class GitlabController extends Controller {

    protected function getGitlabUserInfoEndpoint(User $user) {
        try {
            $userInfo = $this->getGitlabUserInfo($user);

            return response()->json([
                'user' => $userInfo
            ]);

        } catch (RuntimeException $exception) {
            return $this->getResponseForException($exception);
        }

    }

    protected function getGitlabUserRepositoriesEndpoint(User $user) {
        try {
            $repositories = $this->getGitlabUserRepositories($user);

            return response()->json([
                'repositories' => $repositories,
            ]);


        } catch (RuntimeException $exception) {
            return $this->getResponseForException($exception);
        }
    }

    protected function getGitlabUserRepositoryEndpoint(User $user, $identifier) {
        try {

            if(filter_var($identifier, FILTER_VALIDATE_INT)) {
                $repository = $this->getGitlabUserRepositoryById($user, $identifier);
            } else {
                $this->throwNotSupportedException();
            }

            return response()->json([
                'repository' => $repository
            ]);

        } catch (RuntimeException $runtimeException) {
            return $this->getResponseForException($runtimeException);
        }
    }

    protected function getGitlabUserRepositoryLanguagesEndpoint(User $user, $identifier) {
        try {
            $languages = $this->getGitlabRepositoryLanguages($user, $identifier);

            return response()->json([
                'languages' => $languages
            ]);

        } catch (RuntimeException $runtimeException) {
            return $this->getResponseForException($runtimeException);
        }
    }


    private function getResponseForException(RuntimeException $runtimeException) {
        if ('No token' === $runtimeException->getMessage()) {
            return response()->json([
                'error' => 'User does not have a gitlab account connected.'
            ]);
        } else if ('404 Project Not Found' === $runtimeException->getMessage()) {
            return response()->json([
                'error' => "Could not find repository"
            ]);
        } else {
            return response()->json([
                'exception' => $runtimeException->getMessage()
            ]);
        }
    }

    private function throwNoGitlabTokenException() {
        throw new RuntimeException("No token");
    }

    private function throwGitlabNotFoundException() {
        throw new RuntimeException('404 Project Not Found');
    }
    private function throwNotSupportedException() {
        throw new RuntimeException('Not supported');
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \RuntimeException
     */
    protected function getGitlabUserInfo(User $user) {
        $token = $user->gitlab_api_token ?? $this->throwNoGitlabTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_OAUTH_TOKEN);

        return $client->api('users')->user();

    }

    /**
     * @param User $user
     * @return mixed
     * @throws \RuntimeException
     */
    protected function getGitlabUserRepositories(User $user) {
        $token = $user->gitlab_api_token ?? $this->throwNoGitlabTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_OAUTH_TOKEN);

        return $client->api('projects')->all([
            'owned' => true,
        ]);

    }

    private function getGitlabUserRepositoryById(User $user, $identifier) {
        $token = $user->gitlab_api_token ?? $this->throwNoGitlabTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_OAUTH_TOKEN);

        $repo = $client->api('projects')->show($identifier);
        $contributors = sizeof($client->api('repo')->contributors($identifier));
        $branches = sizeof($client->api('repo')->branches($identifier));

        $repo['contributors'] = $contributors;
        $repo['branches'] = $branches;

        return $repo;
    }

    private function getGitlabRepositoryLanguages(User $user, $identifier) {
        $token = $user->gitlab_api_token ?? $this->throwNoGitlabTokenException();

        $client = new Client();
        $client->authenticate($token, Client::AUTH_OAUTH_TOKEN);

        return array_keys($client->api('projects')->languages($identifier));


    }
}
