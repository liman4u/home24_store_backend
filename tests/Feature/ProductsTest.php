<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    //To allow for rollback after each test and migrate it before next test
    use DatabaseMigrations;

    /**
     * @test
     *
     * Test: GET /api.
     */
    public function testApi()
    {
        $this->get('/api')
            ->assertJson([
                'Products' => 'Styled chair'
            ]);
    }


}
