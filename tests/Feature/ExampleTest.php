<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The homepage redirects to login (or dashboard) when the app is
     * behind auth. So we accept either 200 or 302 — both are valid
     * "the app is alive" responses.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $this->assertContains(
            $response->status(),
            [200, 302],
            'The homepage should return 200 or redirect (302).'
        );
    }
}
