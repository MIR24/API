<?php

namespace Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function testDatabase()
    {
        $this->seed();
        $this->assertDatabaseHas('users', [
            'name' => 'admin',
            'email' => 'test@test.com',
        ]);
        $this->assertDatabaseHas('categories_tv', [
            'name' => 'Непознанное или Загадки и тайны',
        ]);
        $this->assertDatabaseHas('channels', [
            'name' => 'МИР ПРЕМИУМ',
        ]);
    }
}
