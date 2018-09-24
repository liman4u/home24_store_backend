<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    /**
     * @var \Faker\Generator
     */
    protected $faker;
    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
        parent::setUp();
    }

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {

            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer '.$token;

        }

        return $headers;
    }
}
