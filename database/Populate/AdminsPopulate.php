<?php

namespace Database\Populate;

use App\Models\Admin;

class AdminsPopulate
{
  public static function populate(): void
  {
    $data = [
      'email' => 'admin@gmail.com',
      'password' => '123',
    ];

    $admin = new Admin($data);
    $admin->save();
  }
}
