<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    //To allow for rollback after each test
    use DatabaseMigrations;

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


    /**
     * @test
     *
     * Test: GET /api/product/1.
     */
    public function testCanFetchOneProduct()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/product/1')
            ->assertJsonStructure([
                'data' => [
                    'name','description', 'price', 'quantity'
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/token.
     */
    public function testCanAuthenticateUser()
    {
        $user = factory(User::class)->create(['password' => bcrypt('secret')]);

        $this->post('/api/token',
            ['email' => $user->email, 'password' => 'secret']
        )
            ->assertJsonStructure(['token']);
    }

}
