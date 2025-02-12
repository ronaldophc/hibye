<?php

namespace Database\Populate;

use App\Models\Worker;

class WorkersPopulate
{
  public static function populate()
  {
    $data = [
      'name' => 'Fulano',
      'cpf' => '09631697932',
      'email' => 'fulano@gmail.com',
      'password' => '123',
      'daily_hours' => 8,
      'position_id' => 1,
    ];

    $worker = new Worker($data);
    $worker->save();
  }
}
