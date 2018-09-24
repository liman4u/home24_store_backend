<?php

namespace Tests\Feature;

use App\Models\Product;
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
     * Test: GET /api/products.
     */
    public function testCanFetchProducts()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/products')
            ->assertStatus(Response::HTTP_OK)
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
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'name','description', 'price', 'quantity'
                ]
            ]);
    }




    /**
     * @test
     *
     * Test: POST /api/products.
     */
    public function testCanCreateProduct()
    {

        $user = factory(User::class)->create();

        $product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => $this->faker->randomFloat(2,0,4),
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->post('/api/products', $product, $this->headers($user))
            ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     *
     * Test: POST /api/products.
     */
    public function testCanNotCreateProductWithoutToken()
    {

        $product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => $this->faker->randomFloat(2,0,4),
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->post('/api/products', $product)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @test
     *
     * Test: POST /api/products.
     */
    public function testCanNotCreateProductWithDuplicateName()
    {

        $user = factory(User::class)->create();

        $product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => $this->faker->randomFloat(2,0,4),
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->post('/api/products', $product, $this->headers($user))
            ->assertStatus(Response::HTTP_CREATED);

        $this->post('/api/products', $product, $this->headers($user))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * @test
     *
     * Test: POST /api/products.
     */
    public function testCanUpdateProduct()
    {

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create();

        $data = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => $this->faker->randomFloat(2,0,4),
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->put('/api/products/'.$product->id, $data, $this->headers($user))
            ->assertStatus(Response::HTTP_OK);

    }

    /**
     * @test
     *
     * Test: POST /api/products.
     */
    public function testCanNotUpdateProductWithoutToken()
    {

        $product = factory(Product::class)->create();

        $data = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => $this->faker->randomFloat(2,0,4),
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->put('/api/products/'.$product->id, $data)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

    }


    /**
     * @test
     *
     * Test: DELETE /api/products/$id.
     */
    public function testCanDeleteProduct()
    {
        $user = factory(User::class)->create();

        $product = Product::create($product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => $this->faker->randomFloat(2,0,4),
            'quantity' => $this->faker->randomNumber(3)
        ]);

        $this->delete('/api/products/' . $product->id, [], $this->headers($user))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     *
     * Test: DELETE /api/products/$id.
     */
    public function testCanNotDeleteProductWithInvalidId()
    {
        $user = factory(User::class)->create();


        $this->delete('/api/products/100' , [], $this->headers($user))
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
