<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiSmokeTest extends TestCase
{
    public function test_api_root_is_reachable(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    public function test_profile_requires_authentication(): void
    {
        $response = $this->get('/api/profile');

        $response->assertStatus(401);
    }
}
