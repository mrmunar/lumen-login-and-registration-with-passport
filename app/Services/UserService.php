<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ServiceHelpersTrait;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InvalidInputException;

class UserService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $input)
    {
        if (!$input) {
            throw new InvalidInputException('No input provided');
        }

        if (!isValidRequiredFields($input, [
            'full_name',
            'email',
            'password'
        ])) {
            throw new InvalidInputException('Must provide all required fields');
        }

        $input['password'] = Hash::make($input['password']);

        return $this->user->create($input);
    }
}
