<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $user;
    protected $company;
    protected $apiClient;

    public function __construct(User $user, Company $company, Http $apiClient)
    {
        $this->user = $user;
        $this->company = $company;
        $this->apiClient = $apiClient;
    }

    /**
     * Store a new user and company.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'company_name' => 'required|min:3',
        ]);

        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->input('password'));

            $createdUser = $this->user->create($input);

            $this->company->create([
                'user_id' => $createdUser->id,
                'company_name' => $request->input('company_name'),
            ]);

            $createdUser->createToken('MilestoneApp');

            return $this->responseSuccess([
                'message' => 'Successfully created account'
            ], 201);
        } catch (\Exception $e) {
            return $this->responseError([
                'message' => 'User registration failed',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',
        ]);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post(config('env.api_url') . '/oauth/token', [
                    'grant_type' => config('auth.oauth.grant_type'),
                    'client_id' => config('auth.oauth.client_id'),
                    'client_secret' => config('auth.oauth.client_secret'),
                    'username' => $request->input('email'),
                    'password' => $request->input('password'),
                    'scope' => ''
                ]);

            $returnData = json_decode($response->getBody()->getContents());

            if (!empty($returnData->error)) {
                return $this->responseError([
                    'message' => 'Invalid login credentials'
                ], 401);
            }

            return $this->responseSuccess([
                'message' => 'Successfully logged in',
                'data' => $returnData,
            ]);
        } catch (\Exception $e) {
            return $this->responseError([
                'message' => 'Cannot connect to OAuth API',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
