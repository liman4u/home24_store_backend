<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 23/09/2018
 * Time: 9:48 AM
 */

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationsTest extends TestCase
{
    //To allow for rollback after each test
    use DatabaseMigrations;


    /**
     * @test
     *
     * Test: POST /api/v1/token.
     */
    public function testCanAuthenticateUser()
    {
        $password = str_random(5);

        $user = factory(User::class)->create(['password' => bcrypt($password)]);

        $this->post('/api/v1/token',
            ['email' => $user->email, 'password' => $password]
        )
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'expired_at',
                    'refresh_expired_at'
                ]
            ] );
    }

    /**
     * @test
     *
     * Test: POST /api/v1/token.
     */
    public function testCanNotAuthenticateUserWithEmptyData()
    {

        $this->post('/api/v1/token',[])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     *
     * Test: POST /api/v1/token.
     */
    public function testCanNotAuthenticateUserWithInvalidData()
    {
        $password = str_random(5);

        $this->post('/api/v1/token',['email' => $this->faker->unique()->safeEmail, 'password' => $password])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     *
     * Test: POST /api/v1/token.
     */
    public function testCanNotAuthenticateUserWithWrongPassword()
    {
        $user = factory(User::class)->create();

        $this->post('/api/v1/token',['email' => $user->email, 'password' => 'password'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     *
     * Test: POST /api/v1/register.
     */
    public function testCanRegisterUser()
    {
        $password = str_random(5);

        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password

        ];

        $this->post('/api/v1/register',$user )
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id','name','email'],
                    'token',
                    'expired_at',
                    'refresh_expired_at'
                ]
            ] );
    }

    /**
     * @test
     *
     * Test: POST /api/v1/register.
     */
    public function testCanNotRegisterUserWithEmptyData()
    {
        $user = [];

        $this->post('/api/v1/register',$user )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /**
     * @test
     *
     * Test: POST /api/v1/register.
     */
    public function testCanNotRegisterUserWithInvalidData()
    {
        $password = str_random(5);

        $user = [
            'name' => $this->faker->name,
            'email' => 'john.doe',
            'password' => $password,
            'password_confirmation' => $password

        ];


        $this->post('/api/v1/register',$user )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /**
     * @test
     *
     * Test: GET /api/v1/account.
     */
    public function testCanFetchUserAccount()
    {
        $user = factory(User::class)->create();

        $this->get('/api/v1/account', $this->headers($user))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name','email'
                    ]
                ]
            ]);
    }


    /**
     * @test
     *
     * Test: POST /api/v1/logout.
     */
    public function testCanLogout()
    {

        $user = factory(User::class)->create();


        $this->post('/api/v1/logout',[], $this->headers($user))
            ->assertStatus(Response::HTTP_OK);
    }




}
