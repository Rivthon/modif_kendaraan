<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_real_photo_customizer_renders_all_visual_variants(): void
    {
        $response = $this->get('/custom/honda-civic-turbo');

        $response->assertOk()
            ->assertSee('data-real-vehicle', false)
            ->assertSee('data-color-layer', false)
            ->assertSee('civic-wheel-black.png', false)
            ->assertSee('civic-wheel-bronze.png', false)
            ->assertSee('civic-widebody.png', false)
            ->assertSee('civic-carbon-hood.png', false)
            ->assertSee('civic-racing-livery.png', false)
            ->assertSee('Suspensi & Stance')
            ->assertSee('Kap Mesin')
            ->assertSee('Decal & Livery')
            ->assertDontSee('Stage 1 ECU Tune')
            ->assertDontSee('Performa');
    }
}
