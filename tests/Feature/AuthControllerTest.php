<?php

namespace Tests\Feature;

use Tests\WithDatabaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class AuthControllerTest extends WithDatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_register_user_ceo_success()
    {
        $user = [
            'full_name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '123456',
            'password_confirmation' => '123456',
            'position' => 'CEO',
            'company_name' => $this->faker->company,
        ];

        $this->post(route('auth.register'), $user);
        $this->assertResponseStatus(201);
    }

    public function test_can_register_user_ceo_empty_input_failed()
    {
        $user = [];

        $response = $this->call('POST', route('auth.register'), $user);

        $this->assertEquals(422, $response->status());
        $this->assertJson($response->getContent());
    }

    public function test_can_register_user_ceo_wrong_input_fields_failed()
    {
        $user = [
            'name' => $this->faker->name,
            'email_address' => $this->faker->email,
            'password' => '123456',
            'pos' => 'CEO',
            'companyName' => $this->faker->company,
        ];

        $response = $this->call('POST', route('auth.register'), $user);

        $this->assertEquals(422, $response->status());
        $this->assertJson($response->getContent());
    }
}
