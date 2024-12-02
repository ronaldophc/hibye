<?php

namespace Tests\Browser\Auth;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class AuthenticationTest extends FrameworkTestCase
{
    public function test_should_redirect_if_not_authenticated_to_index(): void
    {
        $page = file_get_contents('http://web/');

        $statusCode = $http_response_header[0];
        $location = $http_response_header[10];

        $this->assertEquals('HTTP/1.1 302 Found', $statusCode);
        $this->assertEquals('Location: /login', $location);
    }
}
