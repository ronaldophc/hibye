<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class AdminController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $this->render('admin/index');
    }
}
