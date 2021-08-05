<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class AuthorizationTest extends TestCase
{
    /**
     * Test user register Success.
     *
     * @return void
     */
    public function testRegisterSuccess()
    {
        $data = [
            'email'     => 'test@gmail.com',
            'name'      => 'Test2',
            'password'  => 'secret1234'
        ];

        $response = $this->post('auth/register', $data);
        $response->seeStatusCode(201);
        $response->seeJsonStructure(['data']);
        User::where('email', 'test@gmail.com')->delete();
    }

    public function testRegisterFailedWhenEmailAlready()
    {
        $user       = User::factory()->create();
        $response   = $this->post('auth/register', $user->toArray());
        $response->seeStatusCode(422);
        $response->seeJsonStructure(['errors']);
    }


    public function testLoginSuccess()
    {
        $user = User::factory()->create();
        $data =[
            'email'     =>  $user->email,
            'password'  =>  'password'
        ];
        $response = $this->post('auth/login', $data);
        $response->seeStatusCode(200);
        $response->seeJsonStructure(['access_token']);
    }

    public function testLoginFailedWhenAccountNotExist()
    {
        $data =[
            'email'     =>  'email_not_exist@gmail.com',
            'password'  =>  'password'];
            
        $response = $this->post('auth/login', $data);
        $response->seeStatusCode(401);
        $response->seeJsonStructure(['error']);
    }
}
