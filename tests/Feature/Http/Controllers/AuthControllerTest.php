<?php

namespace Tests\Feature\Http\Controllers;

use Tests\WithDatabaseTestCase;

class AuthControllerTest extends WithDatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_see_register_route_success()
    {
        $this->assertNotEmpty(route('auth.register'));
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
        $data = json_decode($this->response->getContent());

        $this->response->assertStatus(201);
        $this->assertJson($this->response->getContent());
        $this->response->assertJsonStructure(['success', 'code', 'message', 'data']);
        $this->assertEquals($data->success, true);
        $this->assertEquals($data->code, 201);
        $this->assertNotEmpty($data->message);
        $this->assertEmpty($data->data);
    }

    public function test_can_register_user_ceo_empty_input_failed()
    {
        $user = [];

        $this->post(route('auth.register'), $user);
        $data = json_decode($this->response->getContent());

        $this->response->assertStatus(422);
        $this->assertJson($this->response->getContent());
        $this->response->assertJsonStructure(['success', 'code', 'message', 'errors']);
        $this->assertEquals($data->success, false);
        $this->assertEquals($data->code, 422);
        $this->assertNotEmpty($data->message);
        $this->assertNotEmpty($data->errors);
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

        $this->post(route('auth.register'), $user);
        $data = json_decode($this->response->getContent());

        $this->response->assertStatus(422);
        $this->assertJson($this->response->getContent());
        $this->response->assertJsonStructure(['success', 'code', 'message', 'errors']);
        $this->assertEquals($data->success, false);
        $this->assertEquals($data->code, 422);
        $this->assertNotEmpty($data->message);
        $this->assertNotEmpty($data->errors);
    }

    public function test_can_see_login_route_success()
    {
        $this->assertNotEmpty(route('auth.login'));
    }

    public function test_can_see_login_json_data_returned_success()
    {
        $response = $this->call('POST', route('auth.login'), []);

        $this->assertJson($response->getContent());
    }
}
