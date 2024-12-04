<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $cpf
 * @property string $email
 * @property string $password
 * @property string $address
 * @property string $sex
 * @property int $daily_hours
 * @property string $phone
 */
class Worker extends Model
{
    protected static string $table = 'workers';
    protected static array $columns = ['name', 'email', 'cpf', 'password', 'address', 'sex', 'daily_hours', 'phone'];

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('cpf', $this);
        Validations::notEmpty('daily_hours', $this);

        Validations::uniqueness('cpf', $this);
    }

    public function authenticate(string $password): bool
    {
        if ($this->password == null) {
            return false;
        }

        return $password === $this->password;
    }

    public static function findByCpf(string $cpf): Worker | null
    {
        return Worker::findBy(['cpf' => $cpf]);
    }

    public static function findByEmail(string $email): Worker | null
    {
        return Worker::findBy(['email' => $email]);
    }
}
