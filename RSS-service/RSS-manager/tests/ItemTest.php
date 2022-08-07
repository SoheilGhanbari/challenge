<?php


namespace Tests;

class ItemTest extends TestCase
{

    /**
     * /feeds/id/items [GET]
     */
    public function testShouldReturnAllItems(){

        $this->get("feeds/1/items", ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            '*' => 'odata.count',
            'value' => ['*' => [
                'id',
                'user_id',
                'uri',
                'is_favorite',
                'is_bookmarekd',
                'is_read',
                'comments',
                'feed_id',
                'published_at'
            ]
            ]
        ]);
        
    }
    /**
     * /feeds/id/items/id [GET]
     */
    public function testShouldReturnItem(){
        $this->get("feeds/1/items/1", ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'user_id',
                'uri',
                'is_favorite',
                'is_bookmarekd',
                'is_read',
                'comments',
                'feed_id',
                'published_at'
            ]
            ]    
        );
        
    }
    /**
     * /feeds/id/items/id [PUT]
     */
    public function testShouldUpdateItem(){

        $parameters = [
            'is_favorite' => true,
            'is_bookmarekd' => true,
            'is_read' => true,
            'comments' => ['This is test1']
        ];

        $this->put("feeds/2/items/1", $parameters, ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'id',
                'message'
            ]     
        );
    }
    /**
     * /feeds/id/items/id [DELETE]
     */
    public function testShouldDeletefeed(){
        
        $this->delete("feeds/2/items/1", [], ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'id',
                'message'
            ]  
        );
    }

}
