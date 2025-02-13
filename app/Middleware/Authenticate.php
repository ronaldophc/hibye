<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class Authenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check('user')) {
            $this->redirectTo(route('users.login'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
