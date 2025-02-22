<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
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
    protected static array $columns = ['name', 'email', 'cpf', 'password', 'address', 'sex', 'daily_hours', 'phone', 'position_id'];

    public function position(): BelongsTo | null
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('email', $this);
        Validations::notEmpty('name', $this);
        Validations::notEmpty('cpf', $this);
        Validations::notEmpty('daily_hours', $this);
        Validations::notEmpty('password', $this);
        Validations::notEmpty('position_id', $this);

        Validations::uniqueness('email', $this);
        Validations::uniqueness('cpf', $this);
    }

    public function validatesUpdate(): void
    {
        Validations::notEmpty('email', $this);
        Validations::notEmpty('name', $this);
        Validations::notEmpty('cpf', $this);
        Validations::notEmpty('daily_hours', $this);
        Validations::notEmpty('password', $this);
        Validations::notEmpty('position_id', $this);
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

    public function setAttributes(mixed $update_params)
    {
        foreach ($update_params as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getSexString(): string
    {
        return ($this->sex == 'male') ? 'Masculino' : 'Feminino';
    }

    public function verifyPosition(): bool
    {
        return empty($this->position_id);
    }
}
