<?php

namespace Tests\Unit\Lib\Authentication;

use Lib\Authentication\Auth;
use App\Models\Worker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private Worker $worker;

    public function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $this->worker = new Worker([
            'name' => 'Fulano',
            'email' => 'fulano@example.com',
            'password' => '123456',
        ]);
        $this->worker->save();
    }

    public function tearDown(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function test_login(): void
    {
        Auth::login('user', $this->worker);

        $this->assertEquals($this->worker->id, $_SESSION['user']['id']);
    }

    public function test_logout(): void
    {
        Auth::login('user', $this->worker);
        Auth::logout('user');

        $this->assertFalse(Auth::check('user'));
    }

}
