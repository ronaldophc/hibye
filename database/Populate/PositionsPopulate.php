<?php

namespace Database\Populate;

use App\Models\Position;

class PositionsPopulate
{
  public static function populate()
  {
    $data = [
      'name' => 'Gerente',
    ];

    $position = new Position($data);
    $position->save();
  }
}
