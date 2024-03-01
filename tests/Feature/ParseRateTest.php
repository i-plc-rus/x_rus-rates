<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParseRateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_fetch_by_date()
    {
        $this->artisan('rates:get')->assertSuccessful();
    }

    public function test_get_data_from_db()
    {
        $this->get('api/rates')->assertStatus(200);
    }
}
