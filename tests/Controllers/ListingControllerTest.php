<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\ListingController;
use Framework\Database;
use Framework\Session;
use Framework\Authorization;

require_once __DIR__ . '/stubs/loadView.php';
require_once __DIR__ . '/stubs/redirect.php';

class ListingControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_POST = $_GET = $_SERVER = $_SESSION = [];
        // stub loadView & redirect
        require __DIR__ . '/stubs/loadView.php';
        require __DIR__ . '/stubs/redirect.php';
    }

    private function makeControllerWithDb($stmtMap)
    {
        $db = $this->createMock(Database::class);
        // $stmtMap: [ queryString => PDOStatementStub ]
        foreach ($stmtMap as $sql => $stmt) {
            $db->method('query')
                ->with($this->stringContains($sql))
                ->willReturn($stmt);
        }
        $ctrl = $this->getMockBuilder(ListingController::class)
            ->disableOriginalConstructor()
            ->getMock();
        (new \ReflectionProperty($ctrl, 'db'))->setAccessible(true);
        (new \ReflectionProperty($ctrl, 'db'))->setValue($ctrl, $db);
        return $ctrl;
    }

    public function testIndexShowsAllListings()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([(object)['id' => 5]]);
        $ctrl = $this->makeControllerWithDb([
            'SELECT * FROM listings ORDER BY created_at DESC' => $stmt
        ]);
        ob_start();
        $ctrl->index();
        $out = ob_get_clean();
        $j = json_decode($out, true);
        $this->assertSame('listings/index', $j['view']);
        $this->assertCount(1, $j['data']['listings']);
    }

    public function testCreateLoadsForm()
    {
        $ctrl = $this->makeControllerWithDb([]);
        ob_start();
        $ctrl->create();
        $out = ob_get_clean();
        $this->assertStringContainsString('listings/create', $out);
    }

    public function testShowNotFound()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);
        $ctrl = $this->makeControllerWithDb(['SELECT * FROM listings Where id' => $stmt]);

        http_response_code(200);
        ob_start();
        $ctrl->show(['id' => 123]);
        $out = ob_get_clean();
        $this->assertSame(404, http_response_code());
        $this->assertStringContainsString('error', $out);
    }

    public function testShowFound()
    {
        $listing = (object)['id' => 7, 'title' => 'X'];
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetch')->willReturn($listing);
        $ctrl = $this->makeControllerWithDb(['SELECT * FROM listings Where id' => $stmt]);

        ob_start();
        $ctrl->show(['id' => 7]);
        $out = ob_get_clean();
        $this->assertStringContainsString('listings/show', $out);
    }

    public function testStoreValidationError()
    {
        $_POST = []; // empty
        $ctrl = $this->makeControllerWithDb([]);
        ob_start();
        $ctrl->store();
        $out = ob_get_clean();
        // should have called loadView('listings/create') with errors
        $this->assertStringContainsString('listings/create', $out);
        $j = json_decode($out, true);
        $this->assertArrayHasKey('errors', $j['data']);
    }

    public function testStoreSuccessRedirects()
    {
        $_POST = [
            'title' => 'A',
            'description' => 'B',
            'salary' => '100',
            'email' => 'a@b.com',
            'city' => 'C',
            'state' => 'S',
            'phone' => '123'
        ];
        Session::startSession();
        Session::set('user', ['id' => 9]);
        // stub the insert query
        $stmt = $this->createMock(\PDOStatement::class);
        $ctrl = $this->makeControllerWithDb(['INSERT INTO listings' => $stmt]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /listings');
        $ctrl->store();
    }

    public function testDestroyNotFound()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);
        $ctrl = $this->makeControllerWithDb(['SELECT * FROM listings WHERE id' => $stmt]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /');
        $ctrl->destroy(['id' => 1]);
    }

    public function testDestroyNotAuthorized()
    {
        $listing = (object)['id' => 2, 'user_id' => 5];
        $stmt1 = $this->createMock(\PDOStatement::class);
        $stmt1->method('fetch')->willReturn($listing);
        $ctrl = $this->makeControllerWithDb([
            'SELECT * FROM listings WHERE id' => $stmt1
        ]);
        Session::startSession();
        Session::set('user', ['id' => 1]);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /listings/2');
        $ctrl->destroy(['id' => 2]);
    }

    public function testDestroySuccess()
    {
        $listing = (object)['id' => 2, 'user_id' => 1];
        $stmt1 = $this->createMock(\PDOStatement::class);
        $stmt1->method('fetch')->willReturn($listing);
        $stmtDel = $this->createMock(\PDOStatement::class);
        $ctrl = $this->makeControllerWithDb([
            'SELECT * FROM listings WHERE id' => $stmt1,
            'DELETE FROM listings'           => $stmtDel
        ]);
        Session::startSession();
        Session::set('user', ['id' => 1]);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /listings');
        $ctrl->destroy(['id' => 2]);
    }

    // ... similarly: testEditFound, testEditNotFound,
    // testUpdateValidationError, testUpdateSuccess,
    // testSearchReturnsResults ...
}
