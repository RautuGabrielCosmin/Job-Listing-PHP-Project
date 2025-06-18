<?php

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;

// load global helper functions
require_once __DIR__ . '/../../helpers.php';

class HelpersTest extends TestCase
{
    public function testBasePath()
    {
        $p = basePath('foo/bar');
        $this->assertStringEndsWith('config/foo/bar', $p);
    }

    public function testFormatSalary()
    {
        $this->assertSame('$1,000.00', formatSalary('1000'));
        $this->assertSame('$0.00',     formatSalary(''));
    }

    public function testSanitize()
    {
        $dirty = '<script>alert(1)</script>';
        $clean = sanitize($dirty);
        $this->assertStringNotContainsString('<', $clean);
        $this->assertStringNotContainsString('>', $clean);
    }
}
