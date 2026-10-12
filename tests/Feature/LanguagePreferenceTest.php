<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguagePreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_bangla_is_the_default_language_and_users_can_switch_languages(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('হোম')
            ->assertSee('English');

        $this->from(route('home'))
            ->post(route('language.update'), ['locale' => 'en'])
            ->assertRedirect(route('home'))
            ->assertSessionHas('locale', 'en');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Home')
            ->assertSee('বাংলা');

        $this->from(route('home'))
            ->post(route('language.update'), ['locale' => 'bn'])
            ->assertRedirect(route('home'))
            ->assertSessionHas('locale', 'bn');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('হোম');
    }

    public function test_users_cannot_select_an_unsupported_language(): void
    {
        $this->from(route('home'))
            ->post(route('language.update'), ['locale' => 'fr'])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('locale');
    }
}
