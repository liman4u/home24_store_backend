<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 24/09/2018
 * Time: 12:17 PM
 */


namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dingo\Api\Exception\ValidationHttpException;

/**
 * Trait ResponseTrait
 * @package App\Context\Api\Http\Traits
 */
trait ResponseTrait
{
    /**
     * Status code of response
     *
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;


    /**
     * Send success response with Data
     *
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data,$message,$statusCode = Response::HTTP_OK) {

        if(!is_null($data)){
            $response = [
                'success' => true,
                'message' => $message,
                'data'  => $data,
                'status_code' => $statusCode

            ];
        }else{
            $response = [
                'success' => true,
                'message' => $message,
                'status_code' => $statusCode,

            ];
        }



        return response()->json($response, $statusCode);
    }

    /**
     * Send failure response with Message
     *
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function failureResponse($message,$statusCode = Response::HTTP_INTERNAL_SERVER_ERROR) {
        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode
        ];
        return response()->json($response, $statusCode);
    }


    /**
     * Return single item response from the application
     *
     * @param Model $item
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item,$message,$statusCode = Response::HTTP_CREATED)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'  => $item['data'],
            'status_code' => $statusCode

        ];

        return response()->json($response, $statusCode);
    }
    /**
     * Return a json response from the application
     *
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array,$message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'  => $array['data'],
            'status_code' => $this->statusCode


        ];

        return response()->json($response, $this->statusCode);
    }


    /**
     * Return json response with validation errors
     *
     * @param $validator
     */
    protected function respondWithErrors($validator)
    {
        // github like error messages
        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }

        throw new ValidationHttpException($result);
    }
}