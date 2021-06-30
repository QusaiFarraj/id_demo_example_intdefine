<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteShortenersTest extends TestCase
{

    public function test_delete_successful()
    {
        $response = $this->getJson('/api/shorteners');
        $data = $response->decodeResponseJson()['data'];
        if (!empty($data[0])) {
            $deletedUrlResponse = $this->postJson('/api/urldelete', ['short_url' => $data[0]['short_url']]);
            $deletedUrlResponse
                ->assertStatus(200)
                ->assertJson([
                    'message' => 'URL deleted',
                ]);
        }

    }

    public function test_delete_failed()
    {

        $response = $this->postJson('/api/urldelete', ['short_url' => 'https://badurl.com']);
        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'URL not found',
            ]);


    }

}
