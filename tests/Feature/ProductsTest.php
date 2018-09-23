<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
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

    /**
     * @test
     *
     * Test: GET /api/token.
     */
    public function testCanRegisterUser()
    {
        $user = [
            'name' => 'John Doe',
            'email' => 'john.doe@live.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'

        ];

        $this->post('/api/register',$user )
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['name','email','id'],
                    'token'

                ]
               ] );
    }


    /**
     * @test
     *
     * Test: POST /api/products.
     */
    public function testCanCreateProduct()
    {

        $user = factory(User::class)->create(['password' => bcrypt('secret')]);

        $product = [
            'name' => 'White clean sofa',
            'description' => 'White clean sofa at the cheapest price',
            'price' => "12.05",
            'quantity' => 30
        ];

        $this->post('/api/products', $product, $this->headers($user))
            ->assertStatus(201);
    }

}
