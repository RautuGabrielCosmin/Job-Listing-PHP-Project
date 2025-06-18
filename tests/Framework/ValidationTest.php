<?php

namespace Tests\Framework;

use PHPUnit\Framework\TestCase;
use Framework\Validation;

class ValidationTest extends TestCase
{
    public function testStringValidation()
    {
        $this->assertTrue(Validation::string('hello', 1, 5));
        $this->assertFalse(Validation::string('',      1, 5));
        $this->assertFalse(Validation::string('toolong', 1, 3));
    }

    public function testEmailValidation()
    {
        $this->assertNotFalse(Validation::email('a@b.com'));
        $this->assertFalse(Validation::email('not-an-email'));
    }

    public function testMatch()
    {
        $this->assertTrue(Validation::match('foo', 'foo'));
        $this->assertFalse(Validation::match('foo', 'bar'));
    }
}
