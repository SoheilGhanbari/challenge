<?php


namespace Tests;

class FeedTest extends TestCase
{
    /**
     * /feed [POST]
     */
    public function testShouldCreateFeed(){

        $parameters = [
            'url' => 'test.com',
            'tags' => ['test'],
            'title' => 'test',
            'describtion' => 'This is test'
        ];

        $this->post("feeds", $parameters, ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(201);
        $this->seeJsonStructure(
            [
                'id',
                'message'
            ]    
        );
        
    }
    /**
     * /feeds [GET]
     */
    public function testShouldReturnAllFeeds(){

        $this->get("feeds", ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            '*' => 'odata.count',
            'value' => ['*' => [
                'id',
                'user_id',
                'url',
                'tags',
                'title'
            ]
            ]
        ]);
        
    }
    /**
     * /feeds/id [GET]
     */
    public function testShouldReturnFeed(){
        $this->get("feeds/1", ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'user_id',
                'url',
                'tags',
                'title'
            ]
            ]    
        );
        
    }
    /**
     * /feeds/id [PUT]
     */
    public function testShouldUpdateFeed(){

        $parameters = [
            'url' => 'test1.com',
            'tags' => ['test1'],
            'title' => 'test1',
            'describtion' => 'This is test1'
        ];

        $this->put("feeds/1", $parameters, ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'id',
                'message'
            ]     
        );
    }
    /**
     * /feeds/id [DELETE]
     */
    public function testShouldDeletefeed(){
        
        $this->delete("feeds/2", [], ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'id',
                'message'
            ]  
        );
    }

}
