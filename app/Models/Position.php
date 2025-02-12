<?php

namespace App\Models;

use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;

class Position extends Model
{
    protected static string $table = 'positions';
    protected static array $columns = ['name'];

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class, 'position_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::uniqueness('name', $this);
    }
}
