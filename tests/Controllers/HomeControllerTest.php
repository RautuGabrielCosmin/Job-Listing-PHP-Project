<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;
use Framework\Database;

require_once __DIR__ . '/stubs/loadView.php';
require_once __DIR__ . '/stubs/redirect.php';

class HomeControllerTest extends TestCase
{
    public function testIndexLoadsHomeWithLatest6()
    {
        // stub loadView in App\Controllers namespace
        require __DIR__ . '/stubs/loadView.php';

        // mock the DB so query(...)->fetchAll() returns 6 items
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetchAll')->willReturn(array_fill(0, 6, (object)['id' => 1]));

        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM listings ORDER BY created_at DESC LIMIT 6')
            ->willReturn($stmt);

        // create controller without running ctor
        $ctrl = $this->getMockBuilder(HomeController::class)
            ->disableOriginalConstructor()
            ->getMock();

        // inject our stubbed DB
        (new \ReflectionProperty($ctrl, 'db'))->setAccessible(true);
        (new \ReflectionProperty($ctrl, 'db'))->setValue($ctrl, $db);

        // capture output
        ob_start();
        $ctrl->index();
        $out = ob_get_clean();

        $json = json_decode($out, true);
        $this->assertSame('home', $json['view']);
        $this->assertCount(6, $json['data']['listings']);
    }
}
