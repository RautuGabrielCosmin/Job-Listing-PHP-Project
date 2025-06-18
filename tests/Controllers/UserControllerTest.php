<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use Framework\Database;
use Framework\Session;

require_once __DIR__ . '/stubs/loadView.php';
require_once __DIR__ . '/stubs/redirect.php';

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_POST = $_GET = $_SERVER = $_SESSION = [];
    }

    private function makeControllerWithDb($stmtMap = [])
    {
        $db = $this->createMock(Database::class);
        foreach ($stmtMap as $sql => $stmt) {
            $db->method('query')
                ->with($this->stringContains($sql))
                ->willReturn($stmt);
        }
        $ctrl = $this->getMockBuilder(UserController::class)
            ->disableOriginalConstructor()
            ->getMock();
        (new \ReflectionProperty($ctrl, 'db'))->setAccessible(true);
        (new \ReflectionProperty($ctrl, 'db'))->setValue($ctrl, $db);
        return $ctrl;
    }

    public function testLoginLoadsView()
    {
        $ctrl = $this->makeControllerWithDb();
        ob_start();
        $ctrl->login();
        $o = ob_get_clean();
        $this->assertStringContainsString('users/login', $o);
    }

    public function testCreateLoadsView()
    {
        $ctrl = $this->makeControllerWithDb();
        ob_start();
        $ctrl->create();
        $o = ob_get_clean();
        $this->assertStringContainsString('users/create', $o);
    }

    public function testStoreValidationErrors()
    {
        $_POST = ['name' => '', 'email' => 'wrong', 'password' => '123', 'password_confirmation' => '321'];
        $ctrl = $this->makeControllerWithDb();
        ob_start();
        $ctrl->store();
        $o = ob_get_clean();
        $j = json_decode($o, true);
        $this->assertStringContainsString('users/create', $j['view']);
        $this->assertArrayHasKey('errors', $j['data']);
    }

    public function testStoreExistingEmail()
    {
        $_POST = ['name' => 'A', 'email' => 'a@b.com', 'city' => 'C', 'state' => 'S', 'password' => 'abcdef', 'password_confirmation' => 'abcdef'];
        // stub SELECT * FROM users
        $stmt1 = $this->createMock(\PDOStatement::class);
        $stmt1->method('fetch')->willReturn((object)['id' => 1]);
        $ctrl = $this->makeControllerWithDb([
            'SELECT * FROM users WHERE email' => $stmt1
        ]);
        ob_start();
        $ctrl->store();
        $o = ob_get_clean();
        $this->assertStringContainsString('users/create', $o);
        $j = json_decode($o, true);
        $this->assertArrayHasKey('errors', $j['data']);
    }

    public function testStoreSuccess()
    {
        $_POST = ['name' => 'A', 'email' => 'a@b.com', 'city' => 'C', 'state' => 'S', 'password' => 'abcdef', 'password_confirmation' => 'abcdef'];
        $stmt1 = $this->createMock(\PDOStatement::class);
        $stmt1->method('fetch')->willReturn(null);
        $stmt2 = $this->createMock(\PDOStatement::class);
        // stub lastInsertId
        $db = $this->createMock(Database::class);
        $db->method('query')
            ->willReturnOnConsecutiveCalls($stmt1, $stmt2);
        $db->conn = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
        $db->conn->method('lastInsertId')->willReturn(123);
        $ctrl = $this->getMockBuilder(UserController::class)
            ->disableOriginalConstructor()
            ->getMock();
        (new \ReflectionProperty($ctrl, 'db'))->setAccessible(true);
        (new \ReflectionProperty($ctrl, 'db'))->setValue($ctrl, $db);

        Session::startSession();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /auth/login');
        $ctrl->store();
    }

    public function testAuthenticateValidationAndWrongCredentials()
    {
        // invalid email & pw
        $_POST = ['email' => 'bad', 'password' => '123'];
        $ctrl = $this->makeControllerWithDb();
        ob_start();
        $ctrl->authenticate();
        $o = ob_get_clean();
        $this->assertStringContainsString('users/login', $o);

        // now valid format but non‐existent user
        $_POST = ['email' => 'a@b.com', 'password' => 'abcdef'];
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);
        $ctrl = $this->makeControllerWithDb(['SELECT * FROM users WHERE email' => $stmt]);
        ob_start();
        $ctrl->authenticate();
        $o = ob_get_clean();
        $this->assertStringContainsString('users/login', $o);
    }

    public function testAuthenticateSuccess()
    {
        $_POST = ['email' => 'a@b.com', 'password' => 'abcdef'];
        $user = (object)[
            'id' => 5,
            'name' => 'N',
            'email' => 'a@b.com',
            'city' => 'C',
            'state' => 'S',
            'password' => password_hash('abcdef', PASSWORD_DEFAULT)
        ];
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetch')->willReturn($user);

        $ctrl = $this->makeControllerWithDb(['SELECT * FROM users WHERE email' => $stmt]);
        Session::startSession();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /auth/login');
        $ctrl->authenticate();
    }

    public function testLogoutClearsSession()
    {
        Session::startSession();
        Session::set('user', ['id' => 1]);
        $ctrl = $this->makeControllerWithDb();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Redirect to /');
        $ctrl->logout();
        $this->assertFalse(Session::check('user'));
    }
}
