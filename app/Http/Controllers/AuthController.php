<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $user;
    protected $company;
    protected $apiClient;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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

        $this->authService->register($request->all());

        return $this->login($request);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',
        ]);

        $returnData = $this->authService->login($request->all());

        return $this->responseSuccess([
            'message' => 'Successfully logged in',
            'data' => $returnData,
        ]);
    }
}
