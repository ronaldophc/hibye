<?php

namespace Tests\Unit\Lib;

use App\Models\Admin;
use Lib\Paginator;
use Tests\TestCase;

class PaginatorTest extends TestCase
{
    private Paginator $paginator;
    /** @var Admin[] $admins */
    private array $admins;

    public function setUp(): void
    {
        parent::setUp();

        for ($i = 0; $i < 10; $i++) {
            $admin = new Admin(['email' => "Admin$i@gmail.com", 'password' => '123456']);
            $admin->save();
            $this->admins[] = $admin;
        }
        $this->paginator = new Paginator(Admin::class, 1, 5, 'admins', ['email']);
    }

    public function test_total_of_registers(): void
    {
        $this->assertEquals(10, $this->paginator->totalOfRegisters());
    }

    public function test_total_of_pages(): void
    {
        $this->assertEquals(2, $this->paginator->totalOfPages());
    }

    public function test_total_of_pages_when_the_division_is_not_exact(): void
    {
        $admins = new Admin(['email' => 'admin11@gmail.com', 'password' => '123456']);
        $admins->save();
        $this->paginator = new Paginator(Admin::class, 1, 5, 'admins', ['email']);

        $this->assertEquals(3, $this->paginator->totalOfPages());
    }

    public function test_previous_page(): void
    {
        $this->assertEquals(0, $this->paginator->previousPage());
    }

    public function test_next_page(): void
    {
        $this->assertEquals(2, $this->paginator->nextPage());
    }

    public function test_has_previous_page(): void
    {
        $this->assertFalse($this->paginator->hasPreviousPage());

        $paginator = new Paginator(Admin::class, 2, 5, 'admins', ['email']);
        $this->assertTrue($paginator->hasPreviousPage());
    }

    public function test_has_next_page(): void
    {
        $this->assertTrue($this->paginator->hasNextPage());

        $paginator = new Paginator(Admin::class, 2, 5, 'admins', ['email']);
        $this->assertFalse($paginator->hasNextPage());
    }

    public function test_is_page(): void
    {
        $this->assertTrue($this->paginator->isPage(1));
        $this->assertFalse($this->paginator->isPage(2));
    }

    public function test_entries_info(): void
    {
        $entriesInfo = 'Mostrando 1 - 5 de 10';
        $this->assertEquals($entriesInfo, $this->paginator->entriesInfo());
    }

    public function test_register_return_all(): void
    {
        $this->assertCount(5, $this->paginator->registers());

        $paginator = new Paginator(Admin::class, 1, 10, 'admins', ['email', 'password']);
        $this->assertEquals($this->admins, $paginator->registers());
    }
}
