<?php

namespace Tests;

class JobTest extends TestCase 
{
    public function testFaildJobs(){
        
        $this->notSeeInDatabase('failed_jobs',['queue' => 'get_items']);
    }
}