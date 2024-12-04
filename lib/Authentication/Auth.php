<?php

namespace Lib\Authentication;

use App\Models\Admin;
use App\Models\Worker;

class Auth
{
    public static function login($typeUser, $user): void
    {
        $_SESSION[$typeUser]['id'] = $user->id;
    }

    public static function user($typeUser): Admin|Worker|null
    {
        if (isset($_SESSION[$typeUser]['id'])) {
            $id = $_SESSION[$typeUser]['id'];
            if ($typeUser === 'admin') {
                return Admin::findById($id);
            }
            return Worker::findById($id);
        }

        return null;
    }

    public static function check($typeUser): bool
    {
        return isset($_SESSION[$typeUser]['id']) && self::user($typeUser) !== null;
    }

    public static function logout($typeUser): void
    {
        unset($_SESSION[$typeUser]['id']);
    }
}
