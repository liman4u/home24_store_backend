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
        $user = factory(User::class)->create(['password' => bcrypt('secret')]);

        $this->post('/api/token',
            ['email' => $user->email, 'password' => 'secret']
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

        $this->post('/api/token',['email' => 'john.doe@live.com', 'password' => 'secret'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     *
     * Test: POST /api/token.
     */
    public function testCanNotAuthenticateUserWithWrongPassword()
    {
        $user = factory(User::class)->create(['password' => bcrypt('secret')]);

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



}
