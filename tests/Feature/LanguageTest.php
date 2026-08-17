<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_locale_is_arabic(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('تسجيل الدخول', false);
    }

    public function test_language_switch_to_english(): void
    {
        $this->session(['locale' => 'ar']);

        $response = $this->get(route('language.switch', 'en'));

        $response->assertRedirect();
        $this->assertEquals('en', session('locale'));

        $this->withSession(['locale' => 'en'])
            ->get('/admin/login')
            ->assertOk()
            ->assertSee('Sign in to access the control panel', false);
    }

    public function test_language_switch_to_arabic(): void
    {
        $this->session(['locale' => 'en']);

        $this->get(route('language.switch', 'ar'))
            ->assertRedirect();

        $this->assertEquals('ar', session('locale'));
    }

    public function test_invalid_locale_falls_back_to_arabic(): void
    {
        $this->get(route('language.switch', 'fr'))
            ->assertRedirect();

        $this->assertEquals('ar', session('locale'));
    }
}
