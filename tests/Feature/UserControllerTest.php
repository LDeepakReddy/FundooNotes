<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_successfulRegistration()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/register', [
                "firstname" => "Dhoni",
                "lastname" => "singh",
                "email" => "dhonims@gmail.com",
                "password" => "dhoni@123",
                "password_confirmation" => "dhoni@123"
            ]);
        $response->assertStatus(201)->assertJson(['message' => 'User successfully registered']);
    }


    public function test_userisAlreadyRegistered()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/register', [
                "firstname" => "Dhoni",
                "lastname" => "singh",
                "email" => "dhoni@gmail.com",
                "password" => "dhoni@123",
                "password_confirmation" => "dhoni@123"
            ]);
        $response->assertStatus(401)->assertJson(['message' => 'The email has already been taken.']);
    }
    public function test_successfulLogin()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/login',
            [
                "email" => "dhoni@gmail.com",
                "password" => "dhoni@123"
            ]
        );
        $response->assertStatus(200)->assertJson(['message' => 'Login successful']);
    }
}

