<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\User;
use App\Transformers\UsersTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;

class AuthController extends BaseController
{
    use ResponseTrait;

    /**
     *  API Login, on success return JWT Auth token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {

        $validator = \Validator::make($request->input(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        try {

            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($this->getCredentials($request))) {

                return $this->failureResponse("Invalid Credentials",Response::HTTP_UNAUTHORIZED);

            }

        } catch (JWTException $e) {

            // something went wrong whilst attempting to encode the token

            return $this->failureResponse("Could Not Create Token");

        }

        $data =  [
                'token' => $token,
                'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
                'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString()

        ];

        // all good so return the token
        return $this->successResponse($data,'Authenticated');

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
     */
    public function logout()
    {
        //dd(JWTAuth::getToken());

        try{

            // get token from header
            $token = JWTAuth::getToken();

            //invalidate token
            JWTAuth::invalidate($token);

            return $this->successResponse(null,'Logout Successful');

        }catch (JWTException $ex){

            return $this->onJwtGenerationError();
        }


    }

    /**
     * Returns the authenticated user
     *
     * @return \Dingo\Api\Http\Response
     */
    public function authenticatedUser()
    {

        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return $this->failureResponse('User Not Found',Response::HTTP_NOT_FOUND);

            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return $this->failureResponse('Token Expired',$e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return $this->failureResponse('Token Invalid',$e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return $this->failureResponse('Token Absent',$e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim

        return $this->response->item($this->user, new UsersTransformer());
    }


    /**
     * Refresh the token
     *
     * @return mixed
     */
    public function refreshToken()
    {

        $token = JWTAuth::getToken();

        if (!$token) {

            return $this->failureResponse('Token Not Provided',Response::HTTP_METHOD_NOT_ALLOWED);

        }

        try {

            $refreshedToken = JWTAuth::refresh($token);

        } catch (JWTException $e) {

            return $this->failureResponse('Not Able To Refresh Token');
        }

        $data =  [
                'token' => $refreshedToken,
                'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
                'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString()

        ];

        // all good so return the refreshed token
        return $this->successResponse($data,'Token Refreshed');

    }

    /**
     * Response should be returned on authorized.
     *
     * @return JsonResponse
     */
    protected function onRegistered($token,User $user)
    {
        $data = [
                'user' => $user,
                'token' => $token,
                'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
                'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),

        ];

        return $this->successResponse($data,'User Registered',Response::HTTP_CREATED);
    }

    /**
     * Response should be returned on authorized.
     *
     * @return JsonResponse
     */
    protected function onAuthorized($token)
    {


        $data = [
            'token' => $token,
            'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),

        ];

        return $this->successResponse($data,'Token Generated');
    }

    /**
     * Response should be returned on error while generate JWT.
     *
     * @return JsonResponse
     */
    protected function onJwtGenerationError()
    {

        return $this->failureResponse('Could Not Create Token');
    }

    /**
     * Response should be returned on invalid credentials.
     *
     * @return JsonResponse
     */
    protected function onUnauthorized()
    {

        return $this->failureResponse('Inavlid Credentials',Response::HTTP_UNAUTHORIZED);
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
        // grab credentials from the request
        return $request->only('email', 'password');
    }
}
