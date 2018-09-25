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
     * Test: GET /api/v1/products.
     */
    public function testCanFetchProducts()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/v1/products')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                       'id', 'name','description', 'price', 'quantity'
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/v1/products.
     */
    public function testCanFetchProductsWithPagination()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/v1/products?limit=3')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3,'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name','description', 'price', 'quantity'
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/v1/products.
     */
    public function testCanFetchProductsWithFilters()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/v1/products?filter=id;name')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name'
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/v1/products.
     */
    public function testCanFetchProductsWithSearch()
    {
        $product = factory(Product::class)->create();

        $this->get('/api/v1/products?search='.$product->name)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name','description', 'price', 'quantity'
                    ]
                ]
            ]);
    }


    /**
     * @test
     *
     * Test: GET /api/v1/product/1.
     */
    public function testCanFetchOneProduct()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/v1/product/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                   'id', 'name','description', 'price', 'quantity'
                ]
            ]);
    }


    /**
     * @test
     *
     * Test: GET /api/v1/product/1.
     */
    public function testCanNotFetchOneProductWithInvalidId()
    {
        $this->seed('ProductsTableSeeder');

        $this->get('/api/v1/product/100')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }




    /**
     * @test
     *
     * Test: POST /api/v1/products.
     */
    public function testCanCreateProduct()
    {

        $user = factory(User::class)->create();

        $product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => "1.50",
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->post('/api/v1/products', $product, $this->headers($user))
            ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     *
     * Test: POST /api/v1/products.
     */
    public function testCanNotCreateProductWithoutToken()
    {

        $product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => "1.50",
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->post('/api/v1/products', $product)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @test
     *
     * Test: POST /api/v1/products.
     */
    public function testCanNotCreateProductWithDuplicateName()
    {

        $user = factory(User::class)->create();

        $product = [
            'name' => $this->faker->text(20),
            'description' => $this->faker->text(20),
            'price' => "1.50",
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->post('/api/v1/products', $product, $this->headers($user))
            ->assertStatus(Response::HTTP_CREATED);

        $this->post('/api/v1/products', $product, $this->headers($user))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * @test
     *
     * Test: POST /api/v1/products.
     */
    public function testCanUpdateProduct()
    {

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create();

        $data = [
            'name' => $this->faker->text(5),
            'description' => $this->faker->text(20),
            'price' => "1.50",
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->put('/api/v1/products/'.$product->id, $data, $this->headers($user))
            ->assertStatus(Response::HTTP_OK);

    }

    /**
     * @test
     *
     * Test: POST /api/v1/products.
     */
    public function testCanNotUpdateProductWithoutToken()
    {

        $product = factory(Product::class)->create();

        $data = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => "1.50",
            'quantity' => $this->faker->randomNumber(3)
        ];

        $this->put('/api/v1/products/'.$product->id, $data)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

    }


    /**
     * @test
     *
     * Test: DELETE /api/v1/products/$id.
     */
    public function testCanDeleteProduct()
    {
        $user = factory(User::class)->create();

        $product = Product::create($product = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(20),
            'price' => "1.50",
            'quantity' => $this->faker->randomNumber(3)
        ]);

        $this->delete('/api/v1/products/' . $product->id, [], $this->headers($user))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     *
     * Test: DELETE /api/v1/products/$id.
     */
    public function testCanNotDeleteProductWithInvalidId()
    {
        $user = factory(User::class)->create();


        $this->delete('/api/v1/products/100' , [], $this->headers($user))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
