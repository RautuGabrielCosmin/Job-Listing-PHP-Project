<?php

namespace Tests\Framework;

use PHPUnit\Framework\TestCase;
use Framework\Authorization;
use Framework\Session;

class AuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testIsOwner()
    {
        Session::set('user', ['id' => 99]);
        $this->assertTrue(Authorization::isOwner(99));
        $this->assertFalse(Authorization::isOwner(1));
    }
}
