<?php

namespace App\Controllers;

use App\Models\Worker;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthController extends Controller
{

  protected string $layout = 'login';

  public function index(): void
  {
    $this->render('auth/login');
  }

  public function authenticate(Request $request): void
  {
    $params = $request->getParam('user');
    $user = Worker::findByEmail($params['email']);

    if (!$user || !$user->authenticate($params['password'])) {
      $this->redirectTo(route('users.login'));
      return;
    }

    Auth::login($user);
    $this->redirectTo(route('root'));

  }
  public function destroy(): void
  {
    Auth::logout();
    FlashMessage::success('Logout realizado com sucesso!');
    $this->redirectTo(route('users.login'));
  }

}
