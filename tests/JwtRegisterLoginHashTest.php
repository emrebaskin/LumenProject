<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class JwtRegisterLoginHashTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * /register [POST]
     *
     * @return void
     */
    public function testRegisterLoginHash()
    {

        // Register
        $this->json('POST', '/register', [
            'name' => 'UnitTestUser',
            'email' => 'unittest@testmail.com',
            'password' => 'unittest',
            'password_confirmation' => 'unittest'
        ]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure(['token']);

        // Login
        $this->json('POST', '/login', [
            'email' => 'unittest@testmail.com',
            'password' => 'unittest'
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['token']);

        // Hash
        $response = json_decode($this->response->getContent());
        $query = http_build_query(['token' => $response->token]);
        $this->get('/hash?'.$query);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['hash']);

    }

}
