<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;

class AuthController extends BaseController
{

    /**
     *  API Login, on success return JWT Auth token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {

            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {

                return response()->json(['error' => 'invalid_credentials'], 401);

            }

        } catch (JWTException $e) {

            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);

        }

        // all good so return the token
        return response()->json(compact('token'));
    }


    /**
     * API Register
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // \Log::info(json_encode($request->all()));
        // \Log::info($request);

        $validator = \Validator::make($request->input(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $password = $request->get('password');

        $attributes = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($password)
        ];

        $user = User::create($attributes);


        try {
            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($this->getCredentials($request))) {

                return $this->onUnauthorized();

            }
        } catch (JWTException $e) {
            // Something went wrong whilst attempting to encode the token
            return $this->onJwtGenerationError();
        }

        // All good so return the token
        return $this->onRegistered($token,$user);


    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {

        $this->validate($request, [
            'token' => 'required'
        ]);

        JWTAuth::invalidate($request->input('token'));

    }

    /**
     * Returns the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticatedUser()
    {

        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['user_not_found'], 404);

            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }


    /**
     * Refresh the token
     *
     * @return mixed
     */
    public function getToken()
    {

        $token = JWTAuth::getToken();

        if (!$token) {

            return $this->response->errorMethodNotAllowed('Token not provided');

        }

        try {

            $refreshedToken = JWTAuth::refresh($token);

        } catch (JWTException $e) {

            return $this->response->errorInternal('Not able to refresh Token');

        }


        return $this->response->withArray(['token' => $refreshedToken]);
    }

    /**
     * What response should be returned on authorized.
     *
     * @return JsonResponse
     */
    protected function onRegistered($token,User $user)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'User Registered',
            'data' => [
                'user' => $user,
                'token' => $token,
                'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
                'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
            ]
        ],Response::HTTP_CREATED);
    }

    /**
     * What response should be returned on authorized.
     *
     * @return JsonResponse
     */
    protected function onAuthorized($token)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Token Generated',
            'data' => [
                'token' => $token,
                'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
                'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
            ]
        ]);
    }

    /**
     * What response should be returned on error while generate JWT.
     *
     * @return JsonResponse
     */
    protected function onJwtGenerationError()
    {
        return new JsonResponse([
            'message' => 'could_not_create_token'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * What response should be returned on invalid credentials.
     *
     * @return JsonResponse
     */
    protected function onUnauthorized()
    {
        return new JsonResponse([
            'message' => 'invalid_credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('email', 'password');
    }
}
