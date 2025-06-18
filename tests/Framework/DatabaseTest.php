<?php

namespace Tests\Framework;

use PHPUnit\Framework\TestCase;
use Framework\Database;
use Exception;

class DatabaseTest extends TestCase
{
    public function testBadCredentialsThrows()
    {
        $this->expectException(Exception::class);
        new Database([
            'host' => 'no-such-host',
            'port' => '3306',
            'dbname' => 'x',
            'username' => 'u',
            'password' => 'p'
        ]);
    }
    // You could also test query() with a real MySQL test database or a stubbed PDO via reflection,
    // but for brevity we’ll keep it at constructor‐failure.
}
