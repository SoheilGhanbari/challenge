<?php


namespace Tests;

class StoryTest extends TestCase
{

    /**
     * /stories [GET]
     */
    public function testShouldReturnAllStories(){

        $this->get("stories", ['x-user-id' => '6e12e55c-a72d-4df1-b59f-b8b754432dba']);
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
                'published_at',
                'url',
                'tags',
                'title'
            ]
            ]
        ]);
        
    }

}
