<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\ResponseCode;

class UserTest extends TestCase
{
    private $urlApi = 'http://localhost:8000/api/auth/';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        //given
        $urlToTest = $this->urlApi.'login';
        $login = [
            "email" => "prueba@example.com",
            "password" => "123456",
            "remember_me" => true
        ];
        $loginError = $login;
        $loginError['password'] = '123';
        
        //when
        $response = $this->postJson($urlToTest, $login);
        $responseError = $this->postJson($urlToTest, $loginError);

        //then
        $response->assertStatus(ResponseCode::HTTP_OK);
        $responseError->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }
}
