<?php

namespace Tests\Framework;

use PHPUnit\Framework\TestCase;
use Framework\Session;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testSetGetCheckClear()
    {
        Session::set('x', 'y');
        $this->assertTrue(Session::check('x'));
        $this->assertSame('y', Session::get('x'));
        Session::clear('x');
        $this->assertFalse(Session::check('x'));
    }

    public function testClearAll()
    {
        Session::set('a', 1);
        Session::clearALL();
        $this->assertEmpty($_SESSION);
    }

    public function testFlashMessage()
    {
        Session::setFlashMessage('foo', 'bar');
        $this->assertSame('bar', Session::getFlashMessage('foo'));
        // second get removes it
        $this->assertNull(Session::getFlashMessage('foo'));
    }
}
