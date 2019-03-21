<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmartTvTest extends TestCase
{
    public function testCatagories()
    {
        $response = $this->json('GET', '/api/smart/v1/categories');
        $response->assertStatus(200);
        $response->assertJsonCount(6);
    }

    public function testChannels()
    {
        $response = $this->json('GET', '/api/smart/v1/channels');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    public function testAchives()
    {
        $response = $this->json('GET', '/api/smart/v1/archives');
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
}
