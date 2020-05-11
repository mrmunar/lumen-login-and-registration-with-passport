<?php

namespace App\Services;

use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Traits\ServiceHelpersTrait;
use Illuminate\Support\Facades\Http;
use App\Exceptions\InvalidInputException;
use App\Exceptions\InvalidLoginException;
use App\Exceptions\ApiConnectionException;
use App\Exceptions\ConfigurationException;

class AuthService
{
    private $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function register(array $input)
    {
        if (!$input) {
            throw new InvalidInputException('No input provided');
        }

        $createdUser = $this->userService->create($input);

        if (empty($input['company_name'])) {
            throw new InvalidInputException('Missing company name field');
        }

        $createdUser->company()->create([
            'user_id' => $createdUser->id,
            'company_name' => $input['company_name'],
        ]);
    }

    public function login(array $input)
    {
        if (!$input) {
            throw new InvalidInputException('No input provided');
        }

        if (!config('auth.oauth')) {
            throw new ConfigurationException('OAuth must be configured properly');
        }

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post(config('env.api_url') . '/oauth/token', [
                    'grant_type'    => config('auth.oauth.grant_type'),
                    'client_id'     => config('auth.oauth.client_id'),
                    'client_secret' => config('auth.oauth.client_secret'),
                    'username'      => array_get($input, 'email', ''),
                    'password'      => array_get($input, 'password', ''),
                    'scope'         => ''
                ]);

            $returnData = json_decode($response->getBody()->getContents());

            if (!empty($returnData->error)) {
                throw new InvalidLoginException('Invalid login credentials');
            }

            return $returnData;
        } catch (InvalidLoginException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ApiConnectionException('Cannot connect to OAuth API'. $e->getMessage());
        }
    }
}
