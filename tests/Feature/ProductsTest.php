<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    //To allow for rollback after each test
    use DatabaseTransactions;

    /**
     * @test
     *
     * Test: GET /api.
     */
    public function testApiCall()
    {
        $this->get('/api')
            ->assertJson([
                'Products' => 'Styled chair'
            ]);
    }


    /**
     * @test
     *
     * Test: GET /api/products.
     */
    public function testCanFetchProducts()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/products')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name','description', 'price', 'quantity'
                    ]
                ]
            ]);
    }

}
