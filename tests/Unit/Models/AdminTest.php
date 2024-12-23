<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use Tests\TestCase;

class AdminTest extends TestCase
{
    private Admin $admin;
    private Admin $admin2;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = new Admin([
            'email' => 'admin@example.com',
            'password' => '123456',
        ]);
        $this->admin->save();

        $this->admin2 = new Admin([
            'email' => 'admin1@example.com',
            'password' => '123456',
        ]);
        $this->admin2->save();
    }

    public function test_should_create_new_user(): void
    {
        $this->assertCount(2, Admin::all());
    }

    public function test_all_should_return_all_users(): void
    {
        $this->admin2->save();

        $users[] = $this->admin->id;
        $users[] = $this->admin2->id;

        $all = array_map(fn ($user) => $user->id, Admin::all());

        $this->assertCount(2, $all);
        $this->assertEquals($users, $all);
    }

    public function test_destroy_should_remove_the_user(): void
    {
        $this->admin->destroy();
        $this->assertCount(1, Admin::all());
    }

    public function test_set_id(): void
    {
        $this->admin->id = 10;
        $this->assertEquals(10, $this->admin->id);
    }

    public function test_set_email(): void
    {
        $this->admin->email = 'outro@example.com';
        $this->assertEquals('outro@example.com', $this->admin->email);
    }

    public function test_errors_should_return_errors(): void
    {
        $user = new Admin();

        $this->assertFalse($user->isValid());
        $this->assertFalse($user->save());
        $this->assertFalse($user->hasErrors());

        $this->assertEquals('não pode ser vazio!', $user->errors('email'));
    }

    public function test_find_by_id_should_return_the_user(): void
    {
        $this->assertEquals($this->admin->id, Admin::findById($this->admin->id)->id);
    }

    public function test_find_by_id_should_return_null(): void
    {
        $this->assertNull(Admin::findById(3));
    }

    public function test_find_by_email_should_return_the_user(): void
    {
        $this->assertEquals($this->admin->id, Admin::findByEmail($this->admin->email)->id);
    }

    public function test_find_by_email_should_return_null(): void
    {
        $this->assertNull(Admin::findByEmail('not.exits@example.com'));
    }

    public function test_authenticate_should_return_the_true(): void
    {
        $this->assertTrue($this->admin->authenticate('123456'));
        $this->assertFalse($this->admin->authenticate('wrong'));
    }

    public function test_authenticate_should_return_false(): void
    {
        $this->assertFalse($this->admin->authenticate(''));
    }
}
