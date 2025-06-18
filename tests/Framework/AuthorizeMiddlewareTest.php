<?php

namespace Tests\Framework\Middleware;

use PHPUnit\Framework\TestCase;
use Framework\Middleware\Authorize;
use Framework\Session;

class AuthorizeMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testGuestCannotAccessAuthPages()
    {
        Session::clearALL();
        // guest trying to reach 'auth' pages should redirect
        $this->expectException(\RuntimeException::class);
        // override redirect in this namespace:
        require_once __DIR__ . '/../stubs/redirect.php';
        (new Authorize())->handle('auth');
    }

    public function testAuthenticatedCannotAccessGuestPages()
    {
        Session::set('user', ['id' => 1]);
        require_once __DIR__ . '/../stubs/redirect.php';
        $this->expectException(\RuntimeException::class);
        (new Authorize())->handle('guest');
    }
}
