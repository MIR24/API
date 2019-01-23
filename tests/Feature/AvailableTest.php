<?php

namespace Tests\Feature;

use Tests\TestCase;

class AvailableTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertLocation("/api/documentation");

        $responseDoc = $this->get("/api/documentation");

        $responseDoc->assertStatus(200);
    }
}
