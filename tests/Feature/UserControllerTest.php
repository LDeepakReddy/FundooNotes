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
                "firstname" => "rohit",
                "lastname" => "shrma",
                "email" => "rohit123@gmail.com",
                "password" => "rohit@123",
                "password_confirmation" => "rohit@123"
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
                    "email" => "rohit123@gmail.com",
                    "password" => "rohit@123"
                ]
            );
        $response->assertStatus(200)->assertJson(['message' => 'Login successful']);
    }

    public function test_UnSuccessfulLogin()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json(
                'POST',
                '/api/login',
                [
                    "email" => "virat@gmail.com",
                    "password" => "virat@12"
                ]
            );
        $response->assertStatus(402)->assertJson(['message' => 'Wrong Password']);
    }

    public function test_SuccessfulForgotPassword()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/forgotPassword', [
                "email" => "ashok@gmail.com"
            ]);

        $response->assertStatus(201)->assertJson(['message' => 'Reset link Sent to your Email']);
    }

    public function test_unsuccessfulForgotPassword()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/forgotPassword', [
                "email" => "fdghfjfk@gmail.com"
            ]);

        $response->assertStatus(402)->assertJson(['message' => 'Email is not registered']);
    }

    public function test_successfulResetPassword()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/resetPassword', [
                "new_password" => "ashok@321",
                "password_confirmation" => "ashok@321",
                "token" => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2ZvcmdvdFBhc3N3b3JkIiwiaWF0IjoxNjU3ODI3MDcyLCJleHAiOjE2NTc4MzA2NzIsIm5iZiI6MTY1NzgyNzA3MiwianRpIjoieVo1dzd3T2Z6a3Vhd0R5MSIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.ygiRUvsVtdvBX-k5QfGInem720plGgisfd88-XLMB24'
            ]);

        $response->assertStatus(200)->assertJson(['message' => 'Password reset successfull!']);
    }

    public function test_unsuccessfulResetPassword()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/resetPassword', [
                "new_password" => "ashok@3214",
                "password_confirmation" => "ashok@3214",
                "token" => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2ZvcmdvdFBhc3N3b3JkIiwiaWF0IjoxNjU3ODI5NjI0LCJleHAiOjE2NTc4MzMyMjQsIm5iZiI6MTY1NzgyOTYyNCwianRpIjoiRzcya0d5TnNpWnJoU2VRUiIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.m-P_nnpfYHmv_MGA3Gc5Em'
            ]);

        $response->assertStatus(401)->assertJson(['message' => 'Invalid Authorization Token']);
    }

    public function test_successfulLogout()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/logout', [
                "token" => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjU3ODMwNjQxLCJleHAiOjE2NTc4MzQyNDEsIm5iZiI6MTY1NzgzMDY0MSwianRpIjoiOGdKWmFVOVJtcld1VFhZOCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.AXXPtoHuZKmIYjhmZj7In1vwX5lVhKXzFBn8pQUJx6Y'
            ]);

        $response->assertStatus(200)->assertJson(['message' => 'User has been logged out']);
    }
}
