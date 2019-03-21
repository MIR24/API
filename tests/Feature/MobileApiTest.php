<?php

namespace Tests\Feature;

use Tests\TestCase;

class MobileApiTest extends TestCase
{
    public function testBasicTest()
    {
        $response = $this->json('GET', '/api/mobile/v1/');
        $response->assertStatus(405);

        $response = $this->json('POST', '/api/mobile/v1/');
        $response->assertStatus(200);
        $response->assertJson(["status" => 403]);
    }
}
