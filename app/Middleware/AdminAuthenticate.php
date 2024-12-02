<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AdminAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check('admin')) {
            $this->redirectTo(route('admins.login'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
