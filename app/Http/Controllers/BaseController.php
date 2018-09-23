<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 22/09/2018
 * Time: 8:09 PM
 */

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\ValidationHttpException;

class BaseController extends Controller
{

    use Helpers;

    // Returns the wrong request
    protected function errorBadRequest($validator)
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