<?php

namespace App\Models;

use App\Helper\Helper;
use App\Services\ProfileImage;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 */
class Admin extends Model
{
    protected static string $table = 'admins';
    protected static array $columns = ['email', 'password', 'profile_image'];

    public function validates(): void
    {
        Validations::notEmpty('email', $this);
        Validations::notEmpty('password', $this);

        Validations::uniqueness('email', $this);
    }

    public function validatesUpdate(): void
    {
        Validations::notEmpty('password', $this);
    }

    public function authenticate(string $password): bool
    {
        if ($this->password == null) {
            return false;
        }

        return $password === $this->password;
    }

    public static function findByEmail(string $email): ?Admin
    {
        return Admin::findBy(['email' => $email]);
    }

    public function profileImage(): ProfileImage
    {
        return new ProfileImage($this);
    }
}
