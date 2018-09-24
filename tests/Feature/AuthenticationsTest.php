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
     * Test: POST /api/token.
     */
    public function testCanAuthenticateUser()
    {
        $password = str_random(5);

        $user = factory(User::class)->create(['password' => bcrypt($password)]);

        $this->post('/api/token',
            ['email' => $user->email, 'password' => $password]
        )
            ->assertJsonStructure(['token']);
    }

    /**
     * @test
     *
     * Test: POST /api/token.
     */
    public function testCanNotAuthenticateUserWithEmptyData()
    {

        $this->post('/api/token',[])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     *
     * Test: POST /api/token.
     */
    public function testCanNotAuthenticateUserWithInvalidData()
    {
        $password = str_random(5);

        $this->post('/api/token',['email' => $this->faker->unique()->safeEmail, 'password' => $password])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     *
     * Test: POST /api/token.
     */
    public function testCanNotAuthenticateUserWithWrongPassword()
    {
        $user = factory(User::class)->create();

        $this->post('/api/token',['email' => $user->email, 'password' => 'password'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     *
     * Test: POST /api/register.
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

        $this->post('/api/register',$user )
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
     * Test: POST /api/register.
     */
    public function testCanNotRegisterUserWithEmptyData()
    {
        $user = [];

        $this->post('/api/register',$user )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /**
     * @test
     *
     * Test: POST /api/register.
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


        $this->post('/api/register',$user )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }


    /**
     * @test
     *
     * Test: POST /api/logout.
     */
    public function testCanLogout()
    {

        $user = factory(User::class)->create();


        $this->post('/api/logout',[], $this->headers($user))
            ->assertStatus(Response::HTTP_OK);
    }


}
