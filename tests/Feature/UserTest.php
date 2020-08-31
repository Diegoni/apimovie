<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\ResponseCode;

class UserTest extends TestCase
{
    private $urlApi = 'http://localhost:8000/api/auth/login';
    private $urlProd = 'https://apimovielitebox.herokuapp.com/api/auth/login';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        //given
        $urlToTest = (env('APP_URL') == "http://localhost") ? $this->urlApi : $this->urlProd;
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
