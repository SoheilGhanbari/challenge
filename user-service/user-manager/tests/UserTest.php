<?php

use Tests\TestCase;

class UserTest extends TestCase 
{
    /**
     * /users [POST]
     */
    public function testShouldCreateUser(){
        $parameters = [
            'name' => 'test',
            'mobile' => '09109109194',
            'password' => 'test@123456',
        ];

        $this->post("users", $parameters, []);
        $this->seeStatusCode(201);
        $this->seeJsonStructure(
            [
                'id',
                'message'
            ]    
        );
    }
    /**
     * /login [POST]
     */
    public function testShouldLogin(){
        $parameters = [
            'identity' => '09109109194',
            'password' => 'test@123456',
        ];

        $this->post("login", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [ 
                'data' =>['token']
            ]    
        );
    }

}