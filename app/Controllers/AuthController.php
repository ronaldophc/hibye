<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Worker;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthController extends Controller
{
    protected string $layout = 'login';

    public function login(): void
    {
        $this->render('auth/login');
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = Worker::findByEmail($params['email']);
        dd($user->position());


        if (!$user || !$user->authenticate($params['password'])) {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
            return;
        }

        FlashMessage::success('Login realizado com sucesso!');
        Auth::login('user', $user);
        $this->redirectTo(route('users.root'));
    }

    public function destroy(): void
    {
        Auth::logout('user');
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('users.login'));
    }

    public function adminLogin(): void
    {
        $this->render('admin/login');
    }

    public function adminAuthenticate(Request $request): void
    {
        $params = $request->getParam('admin');
        $user = Admin::findByEmail($params['email']);

        if (!$user || !$user->authenticate($params['password'])) {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('admins.login'));
            return;
        }

        FlashMessage::success('Login realizado com sucesso!');
        Auth::login('admin', $user);
        $this->redirectTo(route('admins.root'));
    }

    public function adminDestroy(): void
    {
        Auth::logout('admin');
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('admins.login'));
    }
}
