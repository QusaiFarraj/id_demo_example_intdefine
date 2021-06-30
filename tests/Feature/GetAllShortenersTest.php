<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetAllShortenersTest extends TestCase
{
    public function test_get_all_short_url_request()
    {
        $long_url = 'https://'.Str::random(8).'.com';
        $this->postJson('/api/shorteners', ['long_url' => $long_url]);

        $response = $this->getJson('/api/shorteners');
        $data = $response->decodeResponseJson()['data'];
        $latest_url = end($data);
        $last_key_in_arr = array_key_last($data);

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('data.'.$last_key_in_arr, fn ($json) =>
                $json->where('id', $latest_url['id'])
                    ->where('long_url', $latest_url['long_url'])
                    ->etc()
                )
            );


    }
}
