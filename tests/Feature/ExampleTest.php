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
            ->assertSee('data-mod-layer="wheel"', false)
            ->assertSee('data-mod-layer="body"', false)
            ->assertSee('data-mod-layer="hood"', false)
            ->assertSee('data-mod-layer="livery"', false)
            ->assertSee('civic-overlay-wheel-black.png', false)
            ->assertSee('civic-overlay-wheel-bronze.png', false)
            ->assertSee('civic-overlay-bodykit.png', false)
            ->assertSee('civic-overlay-carbon-hood.png', false)
            ->assertSee('civic-overlay-racing-livery.png', false)
            ->assertSee('Suspensi & Stance')
            ->assertSee('Kap Mesin')
            ->assertSee('Decal & Livery')
            ->assertDontSee('Stage 1 ECU Tune')
            ->assertDontSee('Performa');
    }
}
