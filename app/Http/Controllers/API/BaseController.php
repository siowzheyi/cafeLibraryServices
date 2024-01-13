<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'status' => "success",
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function sendHTMLResponse($result, $message)
    {
        $response = [
            'status' => "success",
            'data'    => $result,
            'message' => $message,
        ];


        return $response;
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'status' => "fail",
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendAuthFail($error, $errorMessages = [], $code = 401)
    {
        $response = [
            'status' => "false",
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function sendCustomValidationError($error, $code = 422)
    {
        $response = [
            'status' => "false",
            'errors' => $error,
            'message' => 'The given data was invalid',
        ];
        return response()->json($response, $code);
    }
    
    public function sendHTMLCustomValidationError($error, $code = 422)
    {
        $response = [
            'status' => "false",
            'errors' => $error,
            'message' => 'The given data was invalid',
        ];
        return $response;
    }
}
