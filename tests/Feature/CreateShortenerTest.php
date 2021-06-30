<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateShortenerTest extends TestCase
{

    public function test_create_short_url_request()
    {
        $response = $this->postJson('/api/shorteners', ['long_url' => 'https://'.Str::random(8).'.com']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'ok',
            ]);
    }

    public function test_create_bad_short_url_request()
    {
        $response = $this->postJson('/api/shorteners', ['long_url' => 'www'.Str::random(8).'.com']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'URL not correct format',
            ]);
    }


}
