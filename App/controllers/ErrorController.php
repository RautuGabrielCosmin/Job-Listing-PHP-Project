<?php

namespace App\Controllers;

class ErrorController
{
    /**
     * 404 not found error
     * @return void
     */
    public static function notFound404($message = 'Oops! We can’t find the page you’re looking for')
    {
        http_response_code(404);
        loadView(
            'error',
            [
                'status' => '404',
                'message' => $message
            ]
        );
    } //end of notFOund404($message = 'Resource not found')

    /**
     * 404 unauthorized error
     * @return void
     */
    public static function unauthorized403($message = 'Unauthorized permission denied.')
    {
        http_response_code(403);
        loadView(
            'error',
            [
                'status' => '403',
                'message' => $message
            ]
        );
    } //end of notFOund404($message = 'Resource not found')
}
