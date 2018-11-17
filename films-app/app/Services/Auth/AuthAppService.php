<?php

namespace App\Services\Auth;

use App\Models\User;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class AuthAppService
{
    /** @var ClientInterface */
    protected $httpClient;

    /** @var string */
    protected $authAppBaseUrl;

    public function __construct(ClientInterface $httpClient, string $authAppBaseUrl)
    {
        $this->httpClient = $httpClient;
        $this->authAppBaseUrl = $authAppBaseUrl;
    }

    /**
     * @param string $accessToken
     * @return User|null
     * @throws GuzzleException
     * @throws \InvalidArgumentException
     */
    public function getUserByAccessToken(string $accessToken): ?User
    {
        try {
            $response = $this->httpClient->request('GET', $this->authAppBaseUrl . '/api/user', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            $contents = $response->getBody()->getContents();

            $userData = \GuzzleHttp\json_decode($contents, true);
            if (empty($userData['id']) || empty($userData['name'])) {
                Log::warning("Uncommon response at getting user by access token", [
                    'auth_app_response_contents' => $contents,
                ]);

                return null;
            }

            return User::createByData($userData);
        } catch (ClientException $ex) {
            // Bad token, nothing to do
            return null;
        }
    }
}