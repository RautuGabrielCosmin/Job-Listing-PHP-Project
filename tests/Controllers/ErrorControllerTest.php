<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\ErrorController;

class ErrorControllerTest extends TestCase
{
    public function testNotFound404()
    {
        http_response_code(200);
        ErrorController::notFound404('my msg');
        $this->assertSame(404, http_response_code());
    }

    public function testUnauthorized403()
    {
        http_response_code(200);
        ErrorController::unauthorized403('denied');
        $this->assertSame(403, http_response_code());
    }
}
